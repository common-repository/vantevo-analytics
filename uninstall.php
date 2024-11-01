<?php

//if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

delete_option('vantevo_option_exclude');
delete_option('vantevo_option_404');
delete_option('vantevo_option_domain');
delete_option('vantevo_option_dev');
delete_option('vantevo_option_hash');
delete_option('vantevo_option_source_script');
delete_option('vantevo_option_outbound_link');
delete_option('vantevo_option_manual_pageview');
delete_option('vantevo_option_track_files');
delete_option('vantevo_option_track_extension');
delete_option('vantevo_option_scroll_page_25');
delete_option('vantevo_option_scroll_page_50');
delete_option('vantevo_option_scroll_page_75');
delete_option('vantevo_option_scroll_page_end');
delete_option('vantevo_option_dev_ecommerce');
delete_option('vantevo_option_active_ecommerce');
delete_option('vantevo_option_brand_ecommerce');
delete_option('vantevo_option_source_script_ecommerce');