<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

function vantevo_admin_conainer_page()
{
    $tab = isset($_GET['tab']) ? $_GET['tab'] : null;
?>
    <div class="wrap">
        <!-- Print the page title -->
        <h1><?php _e('Settings Vantevo Analytics', 'vantevo-analytics'); ?></h1>
        <div class="container-vantevo-buttons-header">
            <a href="https://vantevo.io/sites" target="_blank" class="button button-primary" title="Visit dashboard vantevo.io"><?php _e('Dashboard', 'vantevo-analytics'); ?></a>
            <a href="https://vantevo.io/docs" target="_blank" class="button button-default" title="Documentation Vantevo Analytics"><?php _e('Documentation', 'vantevo-analytics'); ?></a>
            <a href="https://vantevo.io/contacts" target="_blank" class="button button-default" title="Contact team Vantevo Analytics"><?php _e('Contacts', 'vantevo-analytics'); ?></a>
        </div>

        <nav class="nav-tab-wrapper">
            <a href="?page=vantevo" class="nav-tab <?php if (!$tab) {
                                                        echo "nav-tab-active";
                                                    } ?>">General</a>
            <a href="?page=vantevo&tab=ecommerce" class="nav-tab <?php if ($tab && $tab == "ecommerce") {
                                                                        echo "nav-tab-active";
                                                                    } ?>">Ecommerce</a>
        </nav>

        <div class="tab-content">
            <?php if (!$tab) { ?>
                <div class="container-vantevo" id="general">

                    <form method="post" action="options.php">
                        <?php settings_fields('vantevo_options_group'); ?>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('404 - Page Not Found', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__('This function will automatically send an event of type %s404%s in which it will save the %surl%s of the page. With this function you will be able to keep track of all 404 pages in a very easy way.', 'vantevo-analytics'), '<code>', '</code>', '<code>', '</code>'); ?></p>
                            <p><?php _e('Caution: If you use the 404 error page tracking feature, your events will be counted as billable monthly page views.', 'vantevo-analytics'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_404" value="OK" <?php if (get_option('vantevo_option_404') == 'OK') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_404" value="NO" <?php if (get_option('vantevo_option_404') == 'NO') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> NO
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Outbound links', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__('With this feature you can monitor all outbound links from your site. For example, every time a user clicks on an outgoing link, the Vantevo script will send an %sOutbound Link%s event in which it will save the %surl%s.', 'vantevo-analytics'), '<code>', '</code>', '<code>', '</code>'); ?></p>
                            <p><?php _e('For more information read ours', 'vantevo-analytics'); ?> <a href="https://vantevo.io/docs/parameters-for-advanced-measurements#outbound-link" title="Documentation - Read more about outbound links" target="_blank" ref="noreferrer"><?php _e('documentation', 'vantevo-analytics'); ?></a></p>
                            <p><?php _e('Caution: If you use outbound link tracking, your events will be counted as billable monthly page views..', 'vantevo-analytics'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_outbound_link" value="OK" <?php if (get_option('vantevo_option_outbound_link') == 'OK') {
                                                                                                                    echo "checked";
                                                                                                                }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_outbound_link" value="NO" <?php if (get_option('vantevo_option_outbound_link') == 'NO') {
                                                                                                                    echo "checked";
                                                                                                                }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Automatic monitoring of file downloads', 'vantevo-analytics'); ?></p>
                            <p><?php _e('Enter a custom list of file extensions for the vantevo script to track and dispatch an event of type File Download.', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__("%sEsempio%s: zip,rar,pdf,xlsx,mp4,avi"), '<strong>', '</strong>'); ?></p>
                            <p><?php _e('For more information read ours', 'vantevo-analytics'); ?> <a href="https://vantevo.io/docs/parameters-for-advanced-measurements#monitoring-the-files-to-download" title="Documentation - Read more about automatic monitoring of file downloads" target="_blank" ref="noreferrer"><?php _e('documentation', 'vantevo-analytics'); ?></a></p>
                            <table style="width: 100%;">
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo" style="width: 100%;">
                                            <input type="text" class="vantevo-input-path" style="width: 100%;" placeholder="<?php _e('zip,rar,pdf,xlsx,mp4,avi', 'vantevo-analytics'); ?>" name="vantevo_option_track_files" value="<?php echo esc_html(get_option('vantevo_option_track_files')); ?>" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p><?php _e("Except the URL do you want to track the file extension as well?", 'vantevo-analytics'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_track_extension" value="OK" <?php if (get_option('vantevo_option_track_extension') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_track_extension" value="NO" <?php if (get_option('vantevo_option_track_extension') == 'NO') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Manual Pageview', 'vantevo-analytics'); ?></p>
                            <p><?php _e('If this feature is enabled, the Vantevo script will not automatically send the site analytics but you will need to add the code on each page to submit the analytics.', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__('The %smanual pageview%s function also allows you to specify custom locations to edit URLs with identifiers, read more on %smanual pageview%s.', 'vantevo-analytics'), '<strong>', '</strong>', '<a href="https://vantevo.io/docs/parameters-for-advanced-measurements#manual-pageview" target="_blank" ref="noreferrer" title="Documentation - Read more about Manual Pageview">', '</a>'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_manual_pageview" value="OK" <?php if (get_option('vantevo_option_manual_pageview') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_manual_pageview" value="NO" <?php if (get_option('vantevo_option_manual_pageview') == 'NO') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Domain', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__('This parameter allows you to save the statistics of this site on a different domain. The domain must be saved in your Vantevo Analytics account. Remember to enable this domain in the domain settings in the "Information -> Authorized Domains" section. Read more about %sauthorized domains%s.', 'vantevo-analytics'), '<a href="https://vantevo.io/docs/domain-settings/information#authorized-domains" target="_blank" ref="noreferrer" title="Documentation - Read more about authorized domains">', '</a>'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="text" class="vantevo-input-path" placeholder="<?php _e('example.com', 'vantevo-analytics'); ?>" name="vantevo_option_domain" value="<?php echo esc_html(get_option('vantevo_option_domain')); ?>" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Development mode', 'vantevo-analytics'); ?></p>
                            <p><?php echo _e('This function is for development mode, it simulates a request on your website without submitting it. You can see the request data in the browser console.', 'vantevo-analytics'); ?></p>

                            <table>
                                <tr valign="middle">
                                    <td>

                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_dev" value="OK" <?php if (get_option('vantevo_option_dev') == 'OK') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_dev" value="NO" <?php if (get_option('vantevo_option_dev') == 'NO') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Hash mode', 'vantevo-analytics'); ?></p>
                            <p><?php echo _e('This parameter allows tracking based on URL hash changes.', 'vantevo-analytics'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_hash" value="OK" <?php if (get_option('vantevo_option_hash') == 'OK') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="radio" name="vantevo_option_hash" value="NO" <?php if (get_option('vantevo_option_hash') == 'NO') {
                                                                                                            echo "checked";
                                                                                                        }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Enable scroll tracking', 'vantevo-analytics'); ?></p>
                            <p><?php echo _e('Send an event whenever a user performs page scrolling for a specific location.The name of the event saved on Vantevo will be <strong>Scroll Tracking</strong>.', 'vantevo-analytics'); ?></p>
                            <table>
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo">
                                            <input type="checkbox" name="vantevo_option_scroll_page_25" value="OK" <?php if (get_option('vantevo_option_scroll_page_25') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('25%', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="checkbox" name="vantevo_option_scroll_page_50" value="OK" <?php if (get_option('vantevo_option_scroll_page_50') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('50%', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="checkbox" name="vantevo_option_scroll_page_75" value="OK" <?php if (get_option('vantevo_option_scroll_page_75') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('75%', 'vantevo-analytics'); ?>
                                        </div>
                                        <div class="td-vantevo">
                                            <input type="checkbox" name="vantevo_option_scroll_page_end" value="OK" <?php if (get_option('vantevo_option_scroll_page_end') == 'OK') {
                                                                                                                        echo "checked";
                                                                                                                    }; ?>> <?php echo _e('100%', 'vantevo-analytics'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Source script', 'vantevo-analytics'); ?></p>
                            <p><?php _e('To not use our link to download the vantevo script, you can put the link to your cdn or download the script locally on your server.', 'vantevo-analytics'); ?></p>
                            <table style="width: 100%;">
                                <tr valign="middle">
                                    <td>
                                        <div class="td-vantevo" style="width: 100%;">
                                            <input type="text" class="vantevo-input-path" style="width: 100%;" placeholder="https://example.com/vantevo.js" name="vantevo_option_source_script" value="<?php echo esc_html(get_option('vantevo_option_source_script')); ?>" />
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <?php submit_button("Save"); ?>
                    </form>
                    <form method="post" action="options.php">
                        <?php settings_fields('vantevo_options_field_exclude'); ?>
                        <div class="blocks-vantevo-forms">
                            <p style="font-weight: bold;"><?php _e('Exclude one or more pages from the statistics', 'vantevo-analytics'); ?></p>
                            <p><?php printf(__('Enter the path of the page you want to exclude from the statistics, for more information on using this function, consult our %sguide%s.', 'vantevo-analytics'), '<a href="https://vantevo.io/docs/how-to-exclude-a-page-from-statistics" target="_blank" title="Documentation - Read more about exclude one or more pages from the statistics" ref="noreferrer">', '</a>'); ?></p>
                            <div class="vantevo-lists-oath">
                                <?php
                                $goals_db = get_option('vantevo_option_exclude');
                                if ($goals_db) {
                                    $goals = explode(",", $goals_db);
                                    if (count($goals) > 0) {
                                        foreach ($goals as $value) {
                                ?>
                                            <div class="vantevo-container-path">
                                                <span class="vantevo-title-path"><?php echo esc_html($value); ?></span>
                                                <span class="vantevo-remove-path" onClick="removeExcludePath('<?php echo esc_html($value); ?>');">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#e90dca" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="12" r="9"></circle>
                                                        <path d="M10 10l4 4m0 -4l-4 4"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <table style="width: 100%;">
                                <tr valign="middle">
                                    <td>
                                        <div class="vantevo-container-input-path">
                                            <label><?php _e('Add pathname', 'vantevo-analytics'); ?></label>
                                            <input type="text" class="vantevo-input-path" placeholder="<?php _e('/page', 'vantevo-analytics'); ?>" name="vantevo_option_exclude" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php submit_button("Save"); ?>
                    </form>
                </div>
            <?php } ?>


            <?php if ($tab && $tab == "ecommerce") { ?>
                <div class="container-vantevo" id="ecommerce">
                    <?php
                    if (class_exists('WooCommerce')) {
                        if (in_array(
                            'woocommerce/woocommerce.php',
                            apply_filters('active_plugins', get_option('active_plugins'))
                        )) {
                            $terms = get_terms(
                                array(
                                    'hide_empty' => false,
                                    'taxonomy' => 'product_tag',
                                )
                            );
                    ?>

                            <form method="post" action="options.php">
                                <?php settings_fields('vantevo_options_group_ecommerce'); ?>
                                <div class="blocks-vantevo-forms">
                                    <table>
                                        <tr valign="middle">
                                            <td>
                                                <div class="td-vantevo">
                                                    <input type="checkbox" name="vantevo_option_active_ecommerce" value="OK" <?php if (get_option('vantevo_option_active_ecommerce') == "OK") {
                                                                                                                                    echo "checked";
                                                                                                                                } ?> /> <?php _e("Activate ecommerce monitoring", "vantevo-analytics"); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="blocks-vantevo-forms">
                                    <table>
                                        <tr valign="middle">
                                            <td>
                                                <div class="td-vantevo">
                                                    <p><?php _e("Select an item you want to use as a Brand.", "vantevo-analytics"); ?></p>
                                                    <p><?php _e("<strong>Category</strong> - the first category of the product will be considered as the brand", "vantevo-analytics"); ?></p>
                                                    <p><?php _e("<strong>Tag</strong> - the first tag of the product will be considered as a brand", "vantevo-analytics"); ?></p>
                                                    <select name="vantevo_option_brand_ecommerce" style="min-width: 300px;">
                                                        <option value=""><?php _e("Select element", "vantevo-analytics"); ?></option>
                                                        <option value="category" <?php if (get_option('vantevo_option_brand_ecommerce') == "category") {
                                                                                        echo 'selected';
                                                                                    } ?>><?php _e("Products category", "vantevo-analytics"); ?></option>
                                                        <option value="tags" <?php if (get_option('vantevo_option_brand_ecommerce') == "tags") {
                                                                                    echo 'selected';
                                                                                } ?>><?php _e("Products tags", "vantevo-analytics"); ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="blocks-vantevo-forms">
                                    <p style="font-weight: bold;"><?php _e('Development mode', 'vantevo-analytics'); ?></p>
                                    <p><?php echo _e('This function is for development mode, it simulates a request on your website without submitting it. You can see the request data in the browser console.', 'vantevo-analytics'); ?></p>

                                    <table>
                                        <tr valign="middle">
                                            <td>

                                                <div class="td-vantevo">
                                                    <input type="radio" name="vantevo_option_dev_ecommerce" value="OK" <?php if (get_option('vantevo_option_dev_ecommerce') == 'OK') {
                                                                                                                    echo "checked";
                                                                                                                }; ?>> <?php echo _e('YES', 'vantevo-analytics'); ?>
                                                </div>
                                                <div class="td-vantevo">
                                                    <input type="radio" name="vantevo_option_dev_ecommerce" value="NO" <?php if (get_option('vantevo_option_dev_ecommerce') == 'NO') {
                                                                                                                    echo "checked";
                                                                                                                }; ?>> <?php echo _e('NO', 'vantevo-analytics'); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="blocks-vantevo-forms">
                                    <p style="font-weight: bold;"><?php _e('Source script', 'vantevo-analytics'); ?></p>
                                    <p><?php _e('To not use our link to download the vantevo script, you can put the link to your cdn or download the script locally on your server.', 'vantevo-analytics'); ?></p>
                                    <table style="width: 100%;">
                                        <tr valign="middle">
                                            <td>
                                                <div class="td-vantevo" style="width: 100%;">
                                                    <input type="text" class="vantevo-input-path" style="width: 100%;" placeholder="https://example.com/vantevo-ecommerce.js" name="vantevo_option_source_script_ecommerce" value="<?php echo esc_html(get_option('vantevo_option_source_script_ecommerce')); ?>" />
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                                <?php submit_button("Save"); ?>
                            </form>

                    <?php
                        } else {
                            echo "<h3>" . _e('It appears that WooCommerce is not active.', 'vantevo-analytics') . "</h3>";
                        }
                    } else {
                        echo "<h3>" . _e('It appears that WooCommerce is not installed.', 'vantevo-analytics') . "</h3>";
                    }
                    ?>
                </div>
            <?php } ?>


        </div>

    </div>

<?php
} ?>