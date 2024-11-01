jQuery(function(){function t(t){var e=["","","","",""];return t&&t.length>0&&t.split("|").forEach((t,r)=>{e[r]=t}),e}function e(t){var e=["","",""];return t&&t.length>0&&t.split("|").forEach((t,r)=>{e[r]=t||""}),e}function r(t){return"string"==typeof t?isNaN(t=parseFloat(t))&&(t=0):"number"!=typeof t&&(t=0),parseFloat(t)}function a(t,e){window.vantevo_ecommerce(t,e,t=>{if(t)return console.error("error hit ecommerce"),!1})}function o(){window.addEventListener("popstate",()=>{setTimeout(c,0)}),c(),jQuery("body").on("adding_to_cart",function(t,r,o){let n=r[0],c=n.closest(".product, .wc-block-grid__product"),u=c&&c.querySelector(".data-vantevo-product-list");if(u)return!1;o&&o.product_id&&fetch(location.origin+"/wp-json/vantevo/v1/product/"+o.product_id).then(t=>t.json()).then(t=>{if(t&&t.id){var r=e([]);let n={item_id:t.sku||t.id,item_name:t.name,currency:t.currency,quantity:1,price:parseFloat(t.price),discount:t.discount,position:o.quantity||1,brand:"",category_1:t.category_1,category_2:t.category_2,category_3:t.category_3,category_4:t.category_4,category_5:t.category_5,variant_1:r[0],variant_2:r[1],variant_3:r[2]};a("add_item_cart",{items:[n]})}})}),document.addEventListener("click",function(o){let n=o.target;try{if(!n||!n.closest(".add_to_cart_button:not(.single_add_to_cart_button, .product_type_variable, .product_type_grouped)"))return!0}catch(c){return!0}let u=n.closest(".product, .wc-block-grid__product"),i=u&&u.querySelector(".data-vantevo-product-list");if(!i)return!0;var d=r(i.getAttribute("data-vantevo-product-price")),v=t(i.getAttribute("data-vantevo-product-categories")),y=e(i.getAttribute("data-vantevo-product-variants"));let l={item_id:i.getAttribute("data-vantevo-product-sku"),item_name:i.getAttribute("data-vantevo-product-name"),currency:i.getAttribute("data-vantevo-product-currency-code"),quantity:1,price:parseFloat(d.toFixed(2)),discount:i.getAttribute("data-vantevo-product-discount"),position:1,brand:i.getAttribute("data-vantevo-product-brand"),category_1:v[0],category_2:v[1],category_3:v[2],category_4:v[3],category_5:v[4],variant_1:y[0],variant_2:y[1],variant_3:y[2]};a("add_item_cart",{items:[l]})});var o,n=[];function c(){var e=location.pathname+""+location.search;if(o!==e){o=e;var n=document.querySelector("#data-vantevo-view-item");if(n){var c=r(n.getAttribute("data-vantevo-product-price")),u=t(n.getAttribute("data-vantevo-product-categories")),i=n.getAttribute("data-vantevo-product-discount")||0;let d={item_id:n.getAttribute("data-vantevo-product-sku"),item_name:n.getAttribute("data-vantevo-product-name"),currency:n.getAttribute("data-vantevo-product-currency-code"),quantity:1,price:parseFloat(c.toFixed(2)),discount:parseFloat(i),position:1,brand:n.getAttribute("data-vantevo-product-brand"),category_1:u[0],category_2:u[1],category_3:u[2],category_4:u[3],category_5:u[4],variant_1:"",variant_2:"",variant_3:""};a("view_item",{items:[d]})}}}jQuery(document).on("found_variation",function(t,e){for(let[r,a]of(n=[],Object.entries(e.attributes)))n.push(r)}),document.addEventListener("click",function(o){let c=o.target;try{if(!c||!c.closest(".single_add_to_cart_button:not(.disabled)"))return!0}catch(u){return!0}let i=c.closest("form.cart");if(!i)return!0;var d=i.querySelectorAll("[name=variation_id]");if(i.classList&&i.classList.contains("grouped_form")){var v=[];let y=document.querySelectorAll(".grouped_form .data-vantevo-product-list-grouped");y.forEach(function(a){var o=a.getAttribute("data-vantevo-product-id"),n=document.querySelectorAll("input[name=quantity\\["+o+"\\]]"),c=1;if(!(n.length>0)||0==(c=n[0]&&n[0].value||1))return!0;var u=r(a.getAttribute("data-vantevo-product-price")),i=t(a.getAttribute("data-vantevo-product-categories")),d=e(a.getAttribute("data-vantevo-product-variants")),y=a.getAttribute("data-vantevo-product-discount")||0;v.push({item_id:a.getAttribute("data-vantevo-product-sku"),item_name:a.getAttribute("data-vantevo-product-name"),currency:a.getAttribute("data-vantevo-product-currency-code"),quantity:parseInt(c),price:parseFloat(u.toFixed(2)),discount:parseFloat(y),position:1,brand:a.getAttribute("data-vantevo-product-brand"),category_1:i[0],category_2:i[1],category_3:i[2],category_4:i[3],category_5:i[4],variant_1:d[0],variant_2:d[1],variant_3:d[2]})}),a("add_item_cart",{items:v})}else if(d.length>0){var l=["","",""];n.forEach((t,e)=>{l[e]=i.querySelector("[name="+t+"]")&&i.querySelector("[name="+t+"]").value});var p=i.querySelector("[name=vantevo_product_sku]")&&i.querySelector("[name=vantevo_product_sku]").value,g=i.querySelector("[name=vantevo_product_name]")&&i.querySelector("[name=vantevo_product_name]").value,m=i.querySelector("[name=vantevo_product_currency_code]")&&i.querySelector("[name=vantevo_product_currency_code]").value,s=i.querySelector("[name=quantity]")&&i.querySelector("[name=quantity]").value,b=i.querySelector("[name=vantevo_product_price]")&&i.querySelector("[name=vantevo_product_price]").value,q=i.querySelector("[name=vantevo_product_discount]")&&i.querySelector("[name=vantevo_product_discount]").value,A=i.querySelector("[name=vantevo_product_brand]")&&i.querySelector("[name=vantevo_product_brand]").value,$=i.querySelector("[name=vantevo_product_categories]")&&i.querySelector("[name=vantevo_product_categories]").value;if(!p||!g||!m||!s)return!0;var _=t($||"");b=r(b);let f={item_id:p,item_name:g,currency:m,quantity:parseInt(s),price:parseFloatproduct_price.toFixed(2),discount:parseFloat(q)||0,position:1,brand:A||"",category_1:_[0],category_2:_[1],category_3:_[2],category_4:_[3],category_5:_[4],variant_1:l[0],variant_2:l[1],variant_3:l[2]};a("add_item_cart",{items:[f]})}else{var p=i.querySelector("[name=vantevo_product_sku]")&&i.querySelector("[name=vantevo_product_sku]").value,g=i.querySelector("[name=vantevo_product_name]")&&i.querySelector("[name=vantevo_product_name]").value,m=i.querySelector("[name=vantevo_product_currency_code]")&&i.querySelector("[name=vantevo_product_currency_code]").value,s=i.querySelector("[name=quantity]")&&i.querySelector("[name=quantity]").value,b=i.querySelector("[name=vantevo_product_price]")&&i.querySelector("[name=vantevo_product_price]").value,q=i.querySelector("[name=vantevo_product_discount]")&&i.querySelector("[name=vantevo_product_discount]").value,A=i.querySelector("[name=vantevo_product_brand]")&&i.querySelector("[name=vantevo_product_brand]").value,$=i.querySelector("[name=vantevo_product_categories]")&&i.querySelector("[name=vantevo_product_categories]").value;if(!p||!g||!m||!s)return!0;var _=t($||"");b=r(b);let S={item_id:p,item_name:g,currency:m,quantity:parseInt(s),price:parseFloat(b.toFixed(2)),discount:parseFloat(q)||0,position:1,brand:A||"",category_1:_[0],category_2:_[1],category_3:_[2],category_4:_[3],category_5:_[4],variant_1:"",variant_2:"",variant_3:""};a("add_item_cart",{items:[S]})}}),document.addEventListener("click",function(o){let n=o.target;try{if(!n||!n.closest(".mini_cart_item a.remove,.product-remove a.remove, .mini_cart_content ul li a.remove"))return!0}catch(c){return!0}var u=0;let i=n.closest(".cart_item"),d=i&&i.querySelectorAll(".product-quantity input.qty");if(d&&0!==d.length)u=d[0].value;else{let v=n.closest(".mini_cart_item, .mini_cart_content ul li");(d=v&&v.querySelectorAll(".quantity"))&&d.length>0&&Number.isNaN(u=parseInt(d[0].textContent))&&(u=0)}if(0===u||(n.getAttribute("data-vantevo-product-sku")||(n=n.parentElement),!n.getAttribute("data-vantevo-product-sku")))return!0;var y=r(n.getAttribute("data-vantevo-product-price")),l=t(n.getAttribute("data-vantevo-product-categories")),p=e(n.getAttribute("data-vantevo-product-variants")),g=n.getAttribute("data-vantevo-product-discount")||0;let m={item_id:n.getAttribute("data-vantevo-product-sku"),item_name:n.getAttribute("data-vantevo-product-name"),currency:n.getAttribute("data-vantevo-product-currency-code"),quantity:parseInt(u),price:parseFloat(y.toFixed(2)),discount:parseFloat(g),position:1,brand:n.getAttribute("data-vantevo-product-brand"),category_1:l[0],category_2:l[1],category_3:l[2],category_4:l[3],category_5:l[4],variant_1:p[0],variant_2:p[1],variant_3:p[2]};a("remove_item_cart",{items:[m]})}),document.addEventListener("click",function(o){let n=o.target;if(!n||!n.closest("[name=update_cart]"))return!0;document.querySelectorAll(".product-quantity input.qty").forEach(function(o){var n=o;let c=n.defaultValue,u=parseInt(n.value);if(isNaN(u)&&(u=c),parseFloat(c)!=parseFloat(u)){let i=n.closest(".cart_item"),d=i&&i.querySelector(".remove");if(!d)return;var v=r(d.getAttribute("data-vantevo-product-price")),y=t(d.getAttribute("data-vantevo-product-categories")),l=e(d.getAttribute("data-vantevo-product-variants")),p=d.getAttribute("data-vantevo-product-discount")||0;if(c<u){let g={item_id:d.getAttribute("data-vantevo-product-sku"),item_name:d.getAttribute("data-vantevo-product-name"),currency:d.getAttribute("data-vantevo-product-currency-code"),quantity:u-c,price:parseFloat(v.toFixed(2)),discount:parseFloat(p),position:1,brand:d.getAttribute("data-vantevo-product-brand"),category_1:y[0],category_2:y[1],category_3:y[2],category_4:y[3],category_5:y[4],variant_1:l[0],variant_2:l[1],variant_3:l[2]};a("add_item_cart",{items:[g]})}else{let m={item_id:d.getAttribute("data-vantevo-product-sku"),item_name:d.getAttribute("data-vantevo-product-name"),currency:d.getAttribute("data-vantevo-product-currency-code"),quantity:c-u,price:parseFloat(v.toFixed(2)),discount:parseFloat(p),position:1,brand:d.getAttribute("data-vantevo-product-brand"),category_1:y[0],category_2:y[1],category_3:y[2],category_4:y[3],category_5:y[4],variant_1:l[0],variant_2:l[1],variant_3:l[2]};a("add_item_cart",{items:[m]})}}})})}document.body?o():window.addEventListener("DOMContentLoaded",o)});