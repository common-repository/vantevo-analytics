<?php

if (!defined('ABSPATH')) {
    exit;
}

class VantevoAnalyticsSettings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'vantevo_register_options_page']);
        add_action('admin_enqueue_scripts', [$this, 'vantevo_enqueue_ajax_script']);
        add_action('admin_init', [$this, 'vantevo_register_settings']);
        add_action('wp_ajax_remove_exclude_path', [$this, 'vantevo_remove_exclude_path']);
        add_filter('plugin_action_links_' . VANTEVO_PLUGIN_BASENAME, [$this, 'vantevo_add_action_links']);
    }

    /**
     * 
     * add link to settings page wordpress admin
     * add link to Docs - vantevo.io/docs
     * 
     * */
    public function vantevo_add_action_links($actions)
    {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=vantevo') . '">' . __('Settings', 'vantevo-analytics') . '</a>',
            '<a href="' . esc_url("https://vantevo.io/docs") . '" target="_blank" rel="noreferrer" title="' . __('Documentation', 'vantevo-analytics') . ' Vantevo Analytics">' . __('Documentation', 'vantevo-analytics') . '</a>',
        );
        return array_merge($actions, $mylinks);
    }

    /**
     * Add "Vantevo Analytics" page on Settings Menu Wodpress
     *
     */
    public function vantevo_register_options_page()
    {
        add_options_page('Vantevo', 'Vantevo Analytics', 'manage_options', 'vantevo', 'vantevo_admin_conainer_page');
    }


    /**
     * Register js & css files
     *
     */
    //"Are you sure you want to delete the path?"
    //"Sei sicuro di voler eliminare il path?"
    public function vantevo_enqueue_ajax_script()
    {
        wp_enqueue_style('vantevo-analytics', plugin_dir_url(__FILE__) . 'css/style.css');

        wp_enqueue_script('jquery');
        wp_deregister_script('vantevo-analytics-admin');
        wp_register_script('vantevo-analytics-admin', plugin_dir_url(__FILE__) . 'js/vantevo-script.js', array(), VANTEVO_VERSION);
        wp_localize_script('vantevo-analytics-admin', 'VantevoAnalytics', array(
            'url'      => admin_url('admin-ajax.php'),
            'remove_exclude_path_nonce'  => wp_create_nonce('remove_exclude_path_nonce'),
            'confirm_message' => __('Are you sure you want to delete the path?', 'vantevo-analytics'),
            'error_message' => __('Error: unable to save data.', 'vantevo-analytics')
        ));
        wp_enqueue_script('vantevo-analytics-admin');
    }

    /**
     * Register options form
     * Exclude Pages , 404 Page Not Found , Outbound Link 
     */

    public function vantevo_register_settings()
    {
       
        add_option('vantevo_option_exclude', '');
        add_option('vantevo_option_domain', '');
        add_option('vantevo_option_track_files', '');
        add_option('vantevo_option_source_script', '');
        add_option('vantevo_option_track_extension', 'NO');
        add_option('vantevo_option_dev', 'NO');
        add_option('vantevo_option_hash', 'NO');
        add_option('vantevo_option_404', 'NO');
        add_option('vantevo_option_outbound_link', 'NO');
        add_option('vantevo_option_manual_pageview', 'NO');
        add_option('vantevo_option_dev_ecommerce', 'NO');
        add_option('vantevo_option_source_script_ecommerce', '');
        add_option('vantevo_option_active_ecommerce', 'NO');
        add_option('vantevo_option_brand_ecommerce', '');
        add_option('vantevo_option_scroll_page_25', '');
        add_option('vantevo_option_scroll_page_50', '');
        add_option('vantevo_option_scroll_page_75', '');
        add_option('vantevo_option_scroll_page_end', '');
        
        register_setting('vantevo_options_group', 'vantevo_option_404');
        register_setting('vantevo_options_group', 'vantevo_option_source_script');
        register_setting('vantevo_options_group', 'vantevo_option_outbound_link');
        register_setting('vantevo_options_group', 'vantevo_option_manual_pageview');
        register_setting('vantevo_options_group', 'vantevo_option_domain');
        register_setting('vantevo_options_group', 'vantevo_option_track_files');
        register_setting('vantevo_options_group', 'vantevo_option_track_extension');
        register_setting('vantevo_options_group', 'vantevo_option_dev');
        register_setting('vantevo_options_group', 'vantevo_option_hash');
        register_setting('vantevo_options_group', 'vantevo_option_scroll_page_25');
        register_setting('vantevo_options_group', 'vantevo_option_scroll_page_50');
        register_setting('vantevo_options_group', 'vantevo_option_scroll_page_75');
        register_setting('vantevo_options_group', 'vantevo_option_scroll_page_end');
        register_setting('vantevo_options_group_ecommerce', 'vantevo_option_dev_ecommerce');
        register_setting('vantevo_options_group_ecommerce', 'vantevo_option_source_script_ecommerce');
        register_setting('vantevo_options_group_ecommerce', 'vantevo_option_active_ecommerce');
        register_setting('vantevo_options_group_ecommerce', 'vantevo_option_brand_ecommerce');
        register_setting('vantevo_options_field_exclude', 'vantevo_option_exclude',  [$this, 'vantevo_verify_exclude']);
    }


    /**
     * Exclude Path - Analytics 
     */
    public function vantevo_verify_exclude($value)
    {
        if (empty($value)) {
            $value = get_option('vantevo_option_exclude'); // ignore the user's changes and use the old database value
            add_settings_error('my_option_notice', 'invalid_vantevo_option_exclude', 'Path is required.');
            return false;
        }

        $goals_db = get_option('vantevo_option_exclude');

        $path = sanitize_text_field($value);

        if ($goals_db) {
            $goals_db = $goals_db . "," . $path;
            return $goals_db;
        }

        return $path;
    }

    /**
     * Remove Exclude Path - Analytics 
     */
    public function vantevo_remove_exclude_path()
    {

        if (!empty($_POST['path']) && check_admin_referer('remove_exclude_path_nonce')) {

            $goals_db = get_option('vantevo_option_exclude');

            $path = sanitize_text_field($_POST['path']);

            if ($goals_db) {
                $goals = explode(",", $goals_db);

                if (in_array($path, $goals)) {

                    if (($key = array_search($path, $goals)) !== false) {
                        unset($goals[$key]);
                    }

                    $save_db = "";
                    if (count($goals) > 0) {
                        $save_db = implode(",", $goals);
                    }
                    delete_option('vantevo_option_exclude');
                    add_option('vantevo_option_exclude', $save_db);
                    echo "success";
                    die();
                    return false;
                }
            }

            echo "error";
            die();
        }
    }
}
