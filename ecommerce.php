<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class VantevoAnalyticsEcommerce
{

    public function __construct()
    {
        add_action('woocommerce_after_add_to_cart_button', [$this, 'vantevo_woocommerce_single_add_to_cart']);
        add_action('woocommerce_after_single_product', [$this, 'vantevo_woocommerce_after_single_product']);
        add_action('woocommerce_checkout_order_review', [$this, 'vantevo_woocommerce_checkout_order_review'], 10);
        add_action('woocommerce_thankyou', [$this, 'vantevo_woocommerce_thankyou'], 10);
        add_filter('woocommerce_blocks_product_grid_item_html', [$this, 'vantevo_woocommerce_blocks_product_grid_item_html'], 99999, 3);
        add_filter('woocommerce_grouped_product_list_column_label', [$this, 'vantevo_woocommerce_grouped_product_list_column_label'], 10, 2);
        add_filter('woocommerce_cart_item_remove_link', [$this, 'vantevo_woocommerce_remove_item_cart'], 10, 2);
        add_action('woocommerce_after_shop_loop_item', [$this, 'vantevo_woocommerce_loop_item']);
        add_action('rest_api_init',  [$this, 'vantevo_woocomerce_rest_api'], 10);
    }

    private function vantevo_is_valid_url($url)
    {
        return  esc_url_raw($url) === $url;
    }

    /**
     * Get categories
     */
    private function vantevo_get_categories($product_id)
    {
        $product_cat = '';

        //single category
        $_product_cats = wp_get_post_terms(
            $product_id,
            'product_cat',
            array(
                'orderby' => 'parent',
                'order'   => 'ASC',
            )
        );


        $first_product_cat = array_pop($_product_cats);

        //multiple categories
        $category_parent_list = get_term_parents_list(
            $first_product_cat->term_id,
            'product_cat',
            array(
                'format'    => 'name',
                'separator' => '|',
                'link'      => false,
                'inclusive' => true,
            )
        );

        $product_cat = $first_product_cat->name;

        if ($category_parent_list && $product_cat) {
            $product_cat = $category_parent_list;
        }

        return $product_cat;
    }

    private function vantevo_get_discount_product($product, $percent)
    {
        $regular_price = (float) $product->get_regular_price();
        if ($product->get_type() == "variable") {
            $regular_price = (float) $product->get_variation_regular_price();
        }

        $sale_price = (float) $product->get_price();
        $discount =  round(($regular_price - $sale_price), 2);

        if ($percent && $regular_price > 0 && $sale_price > 0 && $product->is_on_sale()) {
            $discount = round(100 - ($sale_price / $regular_price * 100), 1);
        }

        return $discount;
    }

    private function vantevo_set_categories($categories)
    {
        $list = array("", "", "", "", "");

        if (!empty($categories)) {
            $categories = explode("|", $categories);
            $c = 1;
            foreach ($categories as $val) {
                if ($c == 1) {
                    $list[0] = $val;
                } else if ($c == 2) {
                    $list[1] = $val;
                } else if ($c == 3) {
                    $list[2] = $val;
                } else if ($c == 4) {
                    $list[3] = $val;
                } else if ($c == 5) {
                    $list[4] = $val;
                }
                $c++;
            }
        }
        return $list;
    }

    private function vantevo_set_variants($val_attributes, $product_id)
    {
        $list = array("", "", "");

        if (!empty($val_attributes)) {
            $j = 1;
            foreach ($val_attributes as $key => $val) {
                $key_attr = str_replace('attribute_', '', $key);
                $list_attributes = wc_get_product_terms($product_id, $key_attr, array('fields' => 'names'));
                $name = "";
                foreach ($list_attributes as $value) {
                    if (strtolower($value) == strtolower($val)) {
                        $name = $value;
                    }
                }

                if ($j == 1) {
                    $list[0] = $name;
                } else if ($j == 2) {
                    $list[1] = $name;
                } else if ($j == 3) {
                    $list[2] = $name;
                }

                $j++;
            }
        }

        return $list;
    }

    private function vantevo_set_brand($product_id, $list_categories)
    {
        $brand = "";
        $get_brand = get_option('vantevo_option_brand_ecommerce');
        if ($get_brand && $get_brand == "category" && count($list_categories) > 0) {
            $brand = $list_categories[0];
        } else if ($get_brand && $get_brand == "tags") {
            $tags = wp_get_post_terms($product_id, 'product_tag');
            if (count($tags) > 0) {
                $brand = $tags[0]->name;
            }
        }
        return $brand;
    }

    /***
     * 
     * 
     */
    public function vantevo_woocommerce_after_single_product()
    {
        global $product;
        $discount       = $this->vantevo_get_discount_product($product, false);
        $categories     = $this->vantevo_get_categories($product->get_type() == "variation" ? $product->get_parent_id() :  $product->get_id());
        $currency_code  = get_woocommerce_currency();

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product->get_id(), $list_categories);



        $view_item = sprintf(
            '<span id="data-vantevo-view-item" style="display:none; visibility:hidden;" data-vantevo-product-id="%s"data-vantevo-product-sku="%s" data-vantevo-product-name="%s" data-vantevo-product-type="%s" data-vantevo-product-price="%s" data-vantevo-product-discount="%s" data-vantevo-product-categories="%s" data-vantevo-product-currency-code="%s" data-vantevo-product-variants="%s" data-vantevo-product-brand="%s"></span>',
            esc_attr($product->get_id()),
            esc_attr($product->get_sku()),
            esc_attr($product->get_title()),
            esc_attr($product->get_type()),
            esc_attr($product->get_price()),
            esc_attr($discount),
            esc_attr($categories),
            esc_attr($currency_code),
            "",
            esc_attr($brand)
        );

        echo $view_item;
    }


    /***
     * 
     * 
     */
    public function vantevo_woocomerce_rest_api()
    {
        register_rest_route('vantevo/v1', '/product/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'vantevo_woocommerce_get_product_by_id'],
        ));
    }

    public function vantevo_woocommerce_get_product_by_id($request)
    {
        $status_code = 200;
        $data = array();
        $id = $request['id'];

        if ($id) {

            $product = wc_get_product($id);
            if (!empty($product)) {
                $product_id     = $product->get_id();
                $product_sku    = $product->get_sku();
                $product_name   = $product->get_title();
                $product_type   = $product->get_type();
                $price          = (float) $product->get_price();
                $discount       = $this->vantevo_get_discount_product($product, false);
                $categories     = $this->vantevo_get_categories($product_type == "variation" ? $product->get_parent_id() :  $product->get_id());
                $currency_code  = get_woocommerce_currency();

                $list_categories = $this->vantevo_set_categories($categories);
                $brand = $this->vantevo_set_brand($product_id, $list_categories);

                $data = array(
                    "sku"           => $product_sku,
                    "id"            => $product_id,
                    "name"          => $product_name,
                    "currency"      => $currency_code,
                    "price"         => $price,
                    "discount"      => $discount,
                    "position"      => 1,
                    "brand"         => $brand,
                    "category_1"    => $list_categories[0],
                    "category_2"    => $list_categories[1],
                    "category_3"    => $list_categories[2],
                    "category_4"    => $list_categories[3],
                    "category_5"    => $list_categories[4],
                    "variant_1"     => "",
                    "variant_2"     => "",
                    "variant_3"     => ""
                );
            } else {
                $status_code = 404;
            }
        } else {
            $status_code = 401;
        }

        $response = new WP_REST_Response($data);
        $response->set_status($status_code);

        return  $response;
    }


    /**
     * Add to cart button single page
     * Details Page
     */
    public function vantevo_woocommerce_single_add_to_cart()
    {
        global $product;


        $product_id     = $product->get_id();
        $product_name   = $product->get_title();
        $product_type   = $product->get_type();
        $product_sku    = $product->get_sku();
        $price          = (float) $product->get_price();
        $discount       = $product_type == "grouped" ? 0 : $this->vantevo_get_discount_product($product, false);
        $categories     = $this->vantevo_get_categories($product_type == "variation" ? $product->get_parent_id() : $product_id);
        $currency_code  = get_woocommerce_currency();

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product_id, $list_categories);


        $attributes_name = array(
            "product_id" => $product_id,
            "product_name" => $product_name,
            "product_type" => $product_type,
            "product_sku" => $product_sku,
            "product_price" => $price,
            "product_discount" => $discount,
            "product_categories" => $categories,
            "product_currency_code" => $currency_code,
            "product_brand" => $brand
        );

        foreach ($attributes_name as $attributes_name_key => $attributes_name_value) {
            echo '<input type="hidden" name="vantevo_' . esc_attr($attributes_name_key) . '" value="' . esc_attr($attributes_name_value) . '" />' . "\n";
        }
    }

    /**
     * Add to cart group products single page
     * 
     */
    public function vantevo_woocommerce_grouped_product_list_column_label($labelvalue, $product)
    {

        if (!isset($product)) {
            return $labelvalue;
        }

        $product_id     = $product->get_id();
        $product_sku    = $product->get_sku();
        $product_name   = $product->get_title();
        $product_type   = $product->get_type();
        $price          = (float) $product->get_price();
        $discount       = $this->vantevo_get_discount_product($product, false);
        $categories     = $this->vantevo_get_categories($product->get_id());
        $currency_code  = get_woocommerce_currency();
        $variants       = "";

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product_id, $list_categories);


        $labelvalue .= sprintf(
            '<span class="data-vantevo-product-list-grouped" style="display:none; visibility:hidden;" data-vantevo-product-id="%s" data-vantevo-product-sku="%s" data-vantevo-product-name="%s" data-vantevo-product-type="%s" data-vantevo-product-price="%s" data-vantevo-product-discount="%s" data-vantevo-product-categories="%s" data-vantevo-product-currency-code="%s" data-vantevo-product-variants="%s" data-vantevo-product-brand="%s"></span>',
            esc_attr($product_id),
            esc_attr($product_sku),
            esc_attr($product_name),
            esc_attr($product_type),
            esc_attr($price),
            esc_attr($discount),
            $categories  ? esc_attr($categories) : "",
            esc_attr($currency_code),
            esc_attr($variants),
            esc_attr($brand),
        );

        return $labelvalue;
    }

    /**
     * 
     * Loop items
     * 
     */
    public function vantevo_woocommerce_loop_item()
    {
        global $product;

        $product_id     = $product->get_id();
        $product_sku    = $product->get_sku();
        $product_name   = $product->get_title();
        $product_type   = $product->get_type();
        $discount       = $this->vantevo_get_discount_product($product, false);
        $price          = (float) $product->get_price();
        $categories     = $this->vantevo_get_categories($product->get_id());
        $currency_code  = get_woocommerce_currency();
        $variants       = "";

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product_id, $list_categories);

        $cartlink_with_data = sprintf(
            'data-vantevo-product-id="%s" data-vantevo-product-sku="%s" data-vantevo-product-name="%s" data-vantevo-product-type="%s" data-vantevo-product-price="%s" data-vantevo-product-discount="%s" data-vantevo-product-categories="%s" data-vantevo-product-currency-code="%s" data-vantevo-product-variants="%s" data-vantevo-product-brand="%s" ',
            esc_attr($product_id),
            esc_attr($product_sku),
            esc_attr($product_name),
            esc_attr($product_type),
            esc_attr($price),
            esc_attr($discount),
            $categories  ? esc_attr($categories) : "",
            esc_attr($currency_code),
            esc_attr($variants),
            esc_attr($brand)
        );
        echo '<span ' . esc_attr($cartlink_with_data) . ' class="data-vantevo-product-list" style="display:none; visibility:hidden;"></span>';
    }


    /***
     * 
     * 
     */
    public function vantevo_woocommerce_blocks_product_grid_item_html($html, $data, $product)
    {


        $product_id     = $product->get_id();
        $product_sku    = $product->get_sku();
        $product_name   = $product->get_title();
        $product_type   = $product->get_type();
        $price          = (float) $product->get_price();
        $discount       = $this->vantevo_get_discount_product($product, false);
        $categories     = $this->vantevo_get_categories($product->get_id());
        $currency_code  = get_woocommerce_currency();
        $variants       = "";

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product_id, $list_categories);

        $cartlink_with_data = sprintf(
            'data-vantevo-product-id="%s" data-vantevo-product-sku="%s" data-vantevo-product-name="%s" data-vantevo-product-type="%s" data-vantevo-product-price="%s" data-vantevo-product-discount="%s" data-vantevo-product-categories="%s" data-vantevo-product-currency-code="%s" data-vantevo-product-variants="%s" data-vantevo-product-brand="%s" ',
            esc_attr($product_id),
            esc_attr($product_sku),
            esc_attr($product_name),
            esc_attr($product_type),
            esc_attr($price),
            esc_attr($discount),
            $categories  ? esc_attr($categories) : "",
            esc_attr($currency_code),
            esc_attr($variants),
            esc_attr($brand)
        );

        $span_item = '<span ' . esc_attr($cartlink_with_data) . ' class="data-vantevo-product-list" style="display:none; visibility:hidden;"></span>';

        return preg_replace('/<li.+class=("|"[^"]+)wc-block-grid__product("|[^"]+")[^<]*>/i', '$0' . $span_item, $html);
    }



    /**
     * 
     * 
     */
    public function vantevo_woocommerce_remove_item_cart($url, $cart_item_key)
    {
        if (!is_object(WC()->cart)) {
            return $url;
        }

        $item = WC()->cart->get_cart_item($cart_item_key);
        $product = $item['data'];
        //$category_id = $product->get_parent_id();
        $val_attributes = $item['variation'];
        $product_id     = $product->get_id();
        $product_sku    = $product->get_sku();
        $product_name   = $product->get_title();
        $product_type   = $product->get_type();
        $price          = (float) $product->get_price();
        $discount       = $this->vantevo_get_discount_product($product, false);
        $categories     = $this->vantevo_get_categories($product_type == "variation" ? $product->get_parent_id() :  $product->get_id());
        $currency_code  = get_woocommerce_currency();
        $variants       = "";


        $list_variants = $this->vantevo_set_variants($val_attributes, $product_id);
        if ($list_variants && count($list_variants) > 0) {
            $variants  = implode("|", $list_variants);
        }

        $list_categories = $this->vantevo_set_categories($categories);
        $brand = $this->vantevo_set_brand($product_id, $list_categories);

        $cartlink_with_data = sprintf(
            'data-vantevo-product-id="%s" data-vantevo-product-sku="%s" data-vantevo-product-name="%s" data-vantevo-product-type="%s" data-vantevo-product-price="%s" data-vantevo-product-discount="%s" data-vantevo-product-categories="%s" data-vantevo-product-currency-code="%s" data-vantevo-product-variants="%s" data-vantevo-product-brand="%s" ',
            esc_attr($product_id),
            esc_attr($product_sku),
            esc_attr($product_name),
            esc_attr($product_type),
            esc_attr($price),
            esc_attr($discount),
            $categories  ? esc_attr($categories) : "",
            esc_attr($currency_code),
            esc_attr($variants),
            esc_attr($brand)
        );
        $link  = str_replace("<a", "<a " . $cartlink_with_data, $url);
        return $link;
    }

    /**
     * 
     * 
     */
    public function vantevo_woocommerce_checkout_order_review()
    {
        if (WC()->cart) {
            $total = (float) WC()->cart->get_total("");
            $coupons = WC()->cart->get_applied_coupons();

            $coupon = "";
            $coupon_value = 0;
            if (sizeof($coupons) > 0) {
                $coupon =  implode("|", $coupons);
            }
            foreach ($coupons as $values) {
                $coupon_value += (float) WC()->cart->get_coupon_discount_amount($values);
            }



            $cart = WC()->cart->get_cart();
            $list_js = array(
                "total" => (float) $total,
                "coupon" => $coupon,
                "coupon_value" => round($coupon_value, 2),
                "items" => array()
            );

            $i = 0;
            foreach ($cart as $values) {
                $i++;
                $product        = $values['data'];
                $val_attributes = $values['variation'];
                $product_id     = $product->get_id();
                $product_name   = $product->get_title();
                $product_type   = $product->get_type();
                $product_sku    = $product->get_sku();
                $quantity       = (float) $values['quantity'];
                $price          = (float) $product->get_price();
                $discount       = $this->vantevo_get_discount_product($product, false);
                $categories     = $this->vantevo_get_categories($product_type == "variation" ? $product->get_parent_id() :  $product_id);
                $currency_code  = get_woocommerce_currency();

                $list_categories = $this->vantevo_set_categories($categories);
                $list_variants = $this->vantevo_set_variants($val_attributes, $product_id);
                $brand = $this->vantevo_set_brand($product_id, $list_categories);

                $list_js["items"][] = array(
                    "item_id"       => $product_sku ? (string) $product_sku : (string) $product_id,
                    "item_name"     => $product_name,
                    "currency"      => $currency_code,
                    "quantity"      => $quantity,
                    "price"         => $price,
                    "discount"      => $discount,
                    "position"      => $i,
                    "brand"         => $brand,
                    "category_1"    => $list_categories[0],
                    "category_2"    => $list_categories[1],
                    "category_3"    => $list_categories[2],
                    "category_4"    => $list_categories[3],
                    "category_5"    => $list_categories[4],
                    "variant_1"     => $list_variants[0],
                    "variant_2"     => $list_variants[1],
                    "variant_3"     => $list_variants[2]
                );
            }


            wc_enqueue_js("
                var items = " . wp_json_encode($list_js) . ";
                window.vantevo_ecommerce('start_checkout', items, (err) => {
                    if (err) {
                        console.error('error start_checkout')
                        return false;
                    }
                });
            ");
        }
    }
    /**
     * 
     * 
     */
    public function vantevo_woocommerce_thankyou($order_id)
    {

        if (!$order_id) return;

        $order = wc_get_order($order_id);
        // $order =  new WC_Order_Item_Product($order_id);

        $time_order     = strtotime($order->get_date_created());
        $time_current   = time();
        $time_interval  = $time_current - $time_order;

        if ($time_interval > 20) {
            return;
        }

        //$total = (float) WC()->cart->get_total("");
        $total = (float) $order->get_total("");
        $coupons = $order->get_coupon_codes();

        $coupon = "";
        $coupon_value = 0;
        if (sizeof($coupons) > 0) {
            $coupon =  implode("|", $coupons);
        }
        foreach ($coupons as $coupon_code) {
            $info_coupon = new WC_Coupon($coupon_code);
            $coupon_value += (float) $info_coupon->get_amount();
        }


        $list_js = array(
            "total" => (float) $total,
            "coupon" => $coupon,
            "coupon_value" => round($coupon_value, 2),
            "payment_type" => $order->get_payment_method_title(),
            "items" => array()
        );


        $i = 0;



        foreach ($order->get_items() as $item) {

            $item_data    = $item->get_data();

            // $variation_id = $item->get_variation_id();
            $variation_id = $item_data['variation_id'];

            $variation    = new WC_Product_Variation($variation_id);
            $attributes   = $variation->get_attributes();
            $i++;

            //$product        = $item->get_product();
            $product = wc_get_product($item['product_id']);


            $list_variants = array("", "", "");
            if ($product->is_type('variation')) {
                $j = 0;
                foreach ($item->get_meta_data() as $metaData) {
                    $attribute = $metaData->get_data();
                    $value = $attribute['value'];
                    $variant = $attribute['key'];
                    $check_attribute  = array_key_exists($variant, $attributes);
                    if ($check_attribute) {
                        $list_variants[$j] =  $value;
                    }
                    $j++;
                }
            }

            $product_id     = $product->get_id();
            $product_name   = $product->get_title();
            $product_type   = $product->get_type();
            $product_sku    = $product->get_sku();
            $quantity       = (float) $item->get_quantity();
            $price          = (float) $product->get_price();
            $discount       = $this->vantevo_get_discount_product($product, false);
            $categories     = $this->vantevo_get_categories($product_type == "variation" ? $product->get_parent_id() :  $product_id);
            $currency_code  = get_woocommerce_currency();

            $list_categories = $this->vantevo_set_categories($categories);

            $brand = $this->vantevo_set_brand($product_id, $list_categories);

            $list_js["items"][] = array(
                "item_id"       => $product_sku ? (string) $product_sku : (string) $product_id,
                "item_name"     => $product_name,
                "currency"      => $currency_code,
                "quantity"      => $quantity,
                "price"         => $price,
                "discount"      => $discount,
                "position"      => $i,
                "brand"         => $brand,
                "category_1"    => $list_categories[0],
                "category_2"    => $list_categories[1],
                "category_3"    => $list_categories[2],
                "category_4"    => $list_categories[3],
                "category_5"    => $list_categories[4],
                "variant_1"     => $list_variants[0],
                "variant_2"     => $list_variants[1],
                "variant_3"     => $list_variants[2]
            );
        }

        wc_enqueue_js("
            var items = " . wp_json_encode($list_js) . ";
            window.vantevo_ecommerce('purchase', items, (err) => {
                if (err) {
                    console.error('error purchase')
                    return false;
                }
            });
        ");
    }
}
