<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class VantevoAnalytics
{

    public function __construct()
    {



        add_action('wp_enqueue_scripts', [$this, 'add_vantevo_script_header']);

        add_filter('script_loader_tag',  [$this, 'add_vantevo_attributes'], 10, 2);
    }


    public static function vantevo_starts_with($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }


    public static function vantevo_ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

    /**
     * 
     * verify valid domain
     * 
     */
    public function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); //length of each label
    }

    /**
     * 
     * add script vantevo to header
     * 
     */
    public function add_vantevo_script_header()
    {

        $src = "https://vantevo.io/js/vantevo.js?ver";
        $srcEcommerce = "https://vantevo.io/js/vantevo.ecommerce.js?ver";

        if (get_option('vantevo_option_source_script')) {
            $src = esc_url(get_option('vantevo_option_source_script')) . "?ver=";
        }

        if (get_option('vantevo_option_source_script_ecommerce')) {
            $srcEcommerce = esc_url(get_option('vantevo_option_source_script_ecommerce')) . "?ver=";
        }

        //add vantevo script 
        wp_enqueue_script("vantevo-analytics",  $src, [], VANTEVO_VERSION, false);

        //register window.vantevo 
        wp_add_inline_script('vantevo-analytics', 'window.vantevo = window.vantevo || function() { (window.vantevo.data = window.vantevo.data || []).push(arguments) };');

        //404 page not found
        if (is_404() && get_option('vantevo_option_404') == "OK") {
            wp_add_inline_script('vantevo-analytics', 'window.vantevo("404");');
        }


        //add vantevo ecommerce script 
        if (get_option("vantevo_option_active_ecommerce") == "OK") {
            wp_enqueue_script("vantevo-analytics-ecommerce",  $srcEcommerce, [], VANTEVO_VERSION, false);
            wp_enqueue_script("vantevo-analytics-woocommerce",  plugin_dir_url(__FILE__) . 'js/vantevo-wocommerce.js', [], VANTEVO_VERSION, false);
            wp_add_inline_script('vantevo-analytics-ecommerce', 'window.vantevo_ecommerce = window.vantevo_ecommerce || function() { (window.vantevo_ecommerce.data = window.vantevo_ecommerce.data || []).push(arguments) };');
        }

        $end = get_option('vantevo_option_scroll_page_end') == 'OK' ? 0 : 1;
        $end_25 = get_option('vantevo_option_scroll_page_25') == 'OK' ? 0 : 1;
        $end_50 = get_option('vantevo_option_scroll_page_50') == 'OK' ? 0 : 1;
        $end_75 = get_option('vantevo_option_scroll_page_75') == 'OK' ? 0 : 1;

        if (!$end || !$end_25 || !$end_50 || !$end_75) {
            wp_add_inline_script('vantevo-analytics', 'var end = ' . (float) esc_js($end) . ', end_25 = ' . (float) esc_js($end_25) . ', end_50 = ' . (float) esc_js($end_50) . ', end_75 = ' . (float) esc_js($end_75) . ';function sendEventScrollTracking(n,r){var o={};o[n]=r,window.vantevo("Scroll Tracking",o,n=>{if(n)return console.error("error event scroll tracking"),!1})}window.onscroll=function(){var n=window.innerHeight,r=Math.ceil(window.pageYOffset),o=document.body.offsetHeight,e=n+r,i=Math.round(e/o*100);!end_25&&i>=25&&i<50&&(end_25=1,sendEventScrollTracking("position","25%")),!end_50&&i>=50&&i<75&&(end_50=1,sendEventScrollTracking("position","50%")),!end_75&&i>=75&&i<100&&(end_75=1,sendEventScrollTracking("position","75%")),!end&&e>=o&&(end=1,sendEventScrollTracking("position","100%"))};');
        }
    }

    /**
     * 
     * add attributes script vantevo
     * 
     */
    public function add_vantevo_attributes($tag, $handle)
    {

        if ('vantevo-analytics' !== $handle && 'vantevo-analytics-ecommerce' !== $handle) {
            return $tag;
        }
        //Development mode Ecommerce
        if ('vantevo-analytics-ecommerce' === $handle && get_option('vantevo_option_dev_ecommerce') == "OK") {
            $tag = str_replace(' src', " data-param-dev src", $tag);
        }
        //Development mode
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_dev') == "OK") {
            $tag = str_replace(' src', " data-param-dev src", $tag);
        }
        //Hash mode
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_hash') == "OK") {
            $tag = str_replace(' src', " data-param-hash src", $tag);
        }

        //Esclude pages
        $exclude_pages = get_option('vantevo_option_exclude');
        if ('vantevo-analytics' === $handle && $exclude_pages) {
            $tag = str_replace(' src', " data-param-exclude='" . esc_html($exclude_pages) . "' src", $tag);
        }

        //manual pageview
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_manual_pageview') == "OK") {
            $tag = str_replace(' src', " data-param-manual-pageview src", $tag);
        }

        //outbound links
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_outbound_link') == "OK") {
            $tag = str_replace(' src', " data-param-outbound-links src", $tag);
        }

        //tracking files
        $extensions = get_option('vantevo_option_track_files');
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_track_files')) {
            $tag = str_replace(' src', " data-param-track-files='" . esc_html($extensions) . "' src", $tag);
        }

        //tracking extensions files
        if ('vantevo-analytics' === $handle && get_option('vantevo_option_track_extension') == "OK") {
            $tag = str_replace(' src', " data-param-save-extension src", $tag);
        }

        //domain
        $domain = get_option('vantevo_option_domain');
        if ($domain && $this->is_valid_domain_name($domain)) {
            $tag = str_replace(' src', " data-param-domain='" . esc_html($domain) . "' src", $tag);
        }

        //add paramters script vantevo-analytics && defer attributes
        return str_replace(' src', " defer src", $tag);
    }
}
