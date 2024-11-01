<?php

/**
 * Plugin Name: Vantevo Analytics
 * Plugin URI: https://vantevo.io/
 * Description: Official Vantevo Analytics plugin for Wordpress. Vantevo Analytics is the alternative platform to Google Analytics, it collects the statistics of your website, but simpler.
 * Version: 1.1.5
 * Author: Vantevo Analytics
 * Text Domain: vantevo-analytics
 * Domain Path: /languages
 * Author URI: https://vantevo.io/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('add_action')) {
    echo 'Error: not found add_action function.';
    exit;
}

if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}


define('VANTEVO_VERSION', '1.1.5');
define('VANTEVO_PLUGIN_FILE', __FILE__);
define('VANTEVO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VANTEVO_PLUGIN_BASENAME', plugin_basename(__FILE__));


function vantevo_init()
{
    if (is_admin()) {
        load_plugin_textdomain('vantevo-analytics', false, basename(dirname(__FILE__)) . '/languages/');
        require_once(VANTEVO_PLUGIN_DIR . 'container.php');
        require_once(VANTEVO_PLUGIN_DIR . 'settings.php');
        new VantevoAnalyticsSettings();
    } else {
        require_once(VANTEVO_PLUGIN_DIR . 'vantevo.php');
        require_once(VANTEVO_PLUGIN_DIR . 'ecommerce.php');
        new VantevoAnalytics();
        new VantevoAnalyticsEcommerce();
    }
}
add_action('plugins_loaded', 'vantevo_init');
