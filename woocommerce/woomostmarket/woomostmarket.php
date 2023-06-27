<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           WooCommerce MOST Market
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce MOST Market
 * Description:       WooCommerce MOST Market - wordpress plugin for implementing the interaction of partners-sellers with the platform MOST Market.
 * Version:           1.0.0
 * Author:            Most market
 * Author URI:        https://most.online
 * Text Domain:       myl10n
 * Domain Path:       /lang
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

__('WooCommerce MOST Market - wordpress plugin for implementing the interaction of partners-sellers with the platform MOST Market.', 'myl10n');
/**
 * Основные настройки плагина
 */
add_action('plugins_loaded', function() {
    load_plugin_textdomain('myl10n', false, dirname(plugin_basename(__FILE__)) . '/lang');
});

function woomostmarket_menu_page() {
    add_menu_page('MOST Market', 'MOST Market', 'read', 'woomostmarket', 'woomostmarket', plugins_url('woomostmarket/img/most_logo_svg.svg'), 26);
    add_submenu_page(
            'woomostmarket', '', __('Setup', 'myl10n'), 'read', 'woomostmarket_general', 'woomostmarket_general_callback'
    );

    add_submenu_page(
            'woomostmarket', '', __('Coupons type', 'myl10n'), 'read', 'woomostmarket_coupon', 'woomostmarket_coupon_callback'
    );

    add_submenu_page(
            'woomostmarket', '', __('Reports', 'myl10n'), 'read', 'woomostmarket_report', 'woomostmarket_report_callback'
    );
}

function woomostmarket_menu_page_short() {
    add_menu_page('MOST Market', 'MOST Market', 'read', 'woomostmarket', 'woomostmarket', plugins_url('woomostmarket/img/most_logo_svg.svg'), 26);
    add_submenu_page(
            'woomostmarket', '', __('Setup', 'myl10n'), 'read', 'woomostmarket_general', 'woomostmarket_general_callback'
    );
}

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (!is_plugin_active('woocommerce/woocommerce.php') OR get_option('woocommerce_enable_coupons') == 'no') {

    add_action('admin_menu', 'woomostmarket_menu_page_short');
} else {
    add_action('admin_menu', 'woomostmarket_menu_page');
}

add_action('admin_menu', 'remove_menus');

function remove_menus() {

    remove_submenu_page('woomostmarket', 'woomostmarket');
}

function activation_plugin() {
    require_once plugin_dir_path(__FILE__) . 'woomostmarket-activator.php';
}

register_activation_hook(__FILE__, 'activation_plugin');

function deactivation_plugin() {
    require_once plugin_dir_path(__FILE__) . 'woomostmarket-deactivator.php';
}

register_deactivation_hook(__FILE__, 'deactivation_plugin');

/**
 * Функция корректировки положения иконки в главном меню
 */
add_action('admin_head', 'my_custom_styles');

function my_custom_styles() {
    echo '<style>
.toplevel_page_woomostmarket .wp-menu-name {
	padding: 12px 10px !important;
}
  </style>';
}

global $most_market_url;
$most_market_url["url"] = get_option('most_market_url');

/**
 *  контент страницы Настройки
 */
function woomostmarket_general_callback() {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        ?>
        <div class="wrap woocommerce">
            <h2><?php _e('WooCommerce not installed or activated', 'myl10n'); ?></h2>
        </div>
        <?php
        die;
    }

    if (get_option('woocommerce_enable_coupons') == 'no') {
        ?>
        <div class="wrap woocommerce">
            <h2><?php _e('Coupons are not allowed in WooCommerce settings!', 'myl10n'); ?></h2>

            <h3><?php _e('Enable coupons in WooCommerce settings', 'myl10n'); ?></h3>
            <h3><?php _e('WooCommerce -> Settings -> Coupon Usage -> Enable Coupon Usage', 'myl10n'); ?></h3>
            <div class="enable_coupon_img">
                <img style="max-width: 700px;" src="/wp-content/plugins/woomostmarket/img/need_enable.png" alt="enable_coupon" />
            </div>
        </div>
        <?php
        die;
    }
    $most_market_url = get_option('most_market_url');
    $most_market_key = get_option('most_market_key');
    $most_market_secret = get_option('most_market_secret');
    $most_market_show_coupon = get_option('most_market_show_coupon');
    ?>


    <link rel="stylesheet" href="/wp-content/plugins/woomostmarket/css/woomostmarket.css">
    <script src="/wp-content/plugins/woomostmarket/js/woomostmarket_scripts.js"></script>

    <div class="wrap woocommerce">
        <h2>
            <span style="position: relative;top: 3px;"><img  src="/wp-content/plugins/woomostmarket/img/most_logo_svg.svg"  /></span>
            <?php _e('MOST Market for WooCommerce - Setup', 'myl10n'); ?></h2>


        <div style="margin-top: 50px;" >
            <div id="content">
                <form style="margin-top: 50px;" method="post" action="">
                    <div class="tab_div" >
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_key"><?php _e('API URL', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_url" id="most_market_url" type="text" style="" value="<?php echo $most_market_url; ?>" class="most_market_input_txt" placeholder="https://test...">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_key"><?php _e('KEY', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_key" id="most_market_key" type="text" style="" value="<?php echo $most_market_key; ?>" class="most_market_input_txt" placeholder="5BcjmnGqXXAszGwTUDUjZaHZ++Z40ukr8...">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_secret"><?php _e('Secret', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_secret" id="most_market_secret" type="text" style="" value="<?php echo $most_market_secret; ?>" class="most_market_input_txt" placeholder="VkFLnjlqQUmvBB8jHvD5rmRa+CiGQ+tnlo...">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_show_coupon_in_order_list"><?php _e('Show coupons in order list', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_show_coupon" id="most_market_show_coupon" type="checkbox" <?php echo (($most_market_show_coupon == '1') ? 'checked' : ''); ?> class="most_market_input_checkbox" value="">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="submit" style="margin-top: 60px; padding-left: 30px; ">
                        <input  type="submit" name="Submit" class="sbmt-btn y_save_btn y_save_btn_all" value="<?php _e('Save', 'myl10n'); ?>" />
                    </p>
                    <div style=" height: 20px; padding-left: 30px; " class="tab_div woocommerce_report_most"  >
                        <p class="woocommerce_saved1_most_p" style="font-size: 20px;font-weight: bold;color: #000;display: none;"><?php _e('Saved', 'myl10n'); ?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Сохранение основных настроек
 */
add_action('wp_ajax_woomostmarket_setup_save', 'woomostmarket_setup_save');

function woomostmarket_setup_save($data) {

    $url = trim($_POST['url']);
    $key = trim($_POST['key']);
    $secret = trim($_POST['secret']);
    $show_coupon = trim($_POST['show_coupon']);


    if ($show_coupon == 1) {

        update_option('most_market_show_coupon', 1, true);
    } else {
        update_option('most_market_show_coupon', 0, true);
    }

    update_option('most_market_key', $key, true);
    update_option('most_market_secret', $secret, true);
    update_option('most_market_url', $url, true);



    echo 1;
    exit;
}

// контент страницы Типы купонов
function woomostmarket_coupon_callback() {

    $most_market_key = get_option('most_market_key');
    $most_market_secret = get_option('most_market_secret');
    $currency_symbol = get_woocommerce_currency_symbol();
    include_once ABSPATH . '/wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETApi.php';
    include_once ABSPATH . '/wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETException.php';
    $most_market = new MOST_MARKET\MOST_MARKETApi();

    $coupon_type_arr = $most_market->GetCouponsData($most_market_key, $most_market_secret);
    if ($coupon_type_arr != null) {
        ?>

        <link href="/wp-content/plugins/woomostmarket/css/select2.min.css" rel="stylesheet" />
        <script src="/wp-content/plugins/woomostmarket/js/select2.min.js"></script>

        <link rel="stylesheet" href="/wp-content/plugins/woomostmarket/css/woomostmarket.css">
        <script src="/wp-content/plugins/woomostmarket/js/woomostmarket_scripts.js"></script>

        <div class="wrap woocommerce">
            <h2>
                <span style="position: relative;top: 3px;"><img  src="/wp-content/plugins/woomostmarket/img/most_logo_svg.svg"  /></span>
                <?php _e('MOST Market - Coupons types', 'myl10n'); ?></h2>
            <div style="margin-top: 50px;" >
                <div id="content">
                    <form style="margin-top: 50px;" method="post" action="">
                        <div class="tab_div woocommerce" >
                            <h3 style="margin-left: 10px;"><?php _e('Coupon types for products', 'myl10n'); ?></h3>
                            <h4 style="margin-left: 10px;"><?php _e('Coupons without product links will not work!', 'myl10n'); ?></h4>
                            <table class="form-table">
                                <tbody><tr valign="top">
                                        <td class="wc_payment_gateways_wrapper" colspan="2">
                                            <table class="wc_gateways widefat">
                                                <tbody class="ui-sortable">
                                                <thead>
                                                    <tr >
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Service', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Discount', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('URL', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Min. order price', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Max. order price', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th style="width:40%;" scope="row" class="titledesc">
                                                            <h4><?php _e('Products', 'myl10n'); ?></h4>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $i = 0;
                                                if ($coupon_type_arr["result"] != null) {
                                                    foreach ($coupon_type_arr["result"]["discounts"] as $coupon_type) {

                                                        if ($coupon_type["basket"] == true)
                                                            continue;
                                                        ?>
                                                        <tr <?php if ($i % 2) echo "class='second_th'"; ?>>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["service"]; ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["amount"] . (($coupon_type["type"] == 'percent') ? ' %' : ' ' . $currency_symbol ); ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["URL"]; ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo (($coupon_type["basketMin"] != '0') ? $coupon_type["basketMin"] . ' ' . $currency_symbol : '-' ); ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo (($coupon_type["basketMax"] != '0') ? $coupon_type["basketMax"] . ' ' . $currency_symbol : '-' ); ?></label>
                                                            </th>
                                                            <th scope="row"  style="width:40%;"  class="titledesc">
                                                                <select multiple="multiple" class="Most_product-search"  style="width: 100%;" name="product_ids[]" data-most_service="<?php echo $coupon_type["service"]; ?>"  data-placeholder="<?php esc_attr_e('Search for a product...', 'myl10n'); ?>" data-action="woocommerce_json_search_products_and_variations">
                                                                    <?php
                                                                    $product_ids = get_option('most_' . $coupon_type["service"]);
                                                                    if ($product_ids) {
                                                                        foreach ($product_ids as $product_id) {
                                                                            $product = wc_get_product($product_id);
                                                                            if (is_object($product)) {
                                                                                echo '<option value="' . esc_attr($product_id) . '"' . selected(true, true, false) . '>' . esc_html(wp_strip_all_tags($product->get_formatted_name())) . '</option>';
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo '<option value=""></option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </th>
                                                        </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                </tbody>
                            </table>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                        <p class="submit" style="margin-top: 60px; padding-left: 30px; ">
                            <input  type="submit" name="Submit" class="sbmt-btn y_save_btn y_save_btn_coupon" value="<?php _e('Save product links', 'myl10n'); ?>" />
                        </p>

                        <div style="  min-height: 50px; padding-left: 30px; " class="tab_div woocommerce_report_most"  >
                            <p class="woocommerce_saved2_most_p" style="font-size: 20px;font-weight: bold;color: #000;display: none;"><?php _e('Saved', 'myl10n'); ?></p>
                        </div>
                        <div class="tab_div woocommerce" style="margin-top:60px;" >
                            <h3 style="margin-left: 10px;"><?php _e('Cart Coupon Types', 'myl10n'); ?></h3>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <td class="wc_payment_gateways_wrapper" colspan="2">
                                            <table class="wc_gateways widefat">
                                                <tbody class="ui-sortable">
                                                <thead>
                                                    <tr >
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Service', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Discount', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('URL', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Min. order price', 'myl10n'); ?></h4>
                                                        </th>
                                                        <th scope="row" class="titledesc">
                                                            <h4><?php _e('Max. order price', 'myl10n'); ?></h4>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <?php
                                                $i = 0;
                                                if ($coupon_type_arr["result"] != null) {
                                                    foreach ($coupon_type_arr["result"]["discounts"] as $coupon_type) {

                                                        if ($coupon_type["basket"] != true)
                                                            continue;
                                                        ?>
                                                        <tr <?php if ($i % 2) echo "class='second_th'"; ?>>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["service"]; ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["amount"] . (($coupon_type["type"] == 'percent') ? ' %' : ' ' . $currency_symbol ); ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo $coupon_type["URL"]; ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo (($coupon_type["basketMin"] != '0') ? $coupon_type["basketMin"] . ' ' . $currency_symbol : '-' ); ?></label>
                                                            </th>
                                                            <th scope="row" class="titledesc">
                                                                <label ><?php echo (($coupon_type["basketMax"] != '0') ? $coupon_type["basketMax"] . ' ' . $currency_symbol : '-' ); ?></label>
                                                            </th>

                                                        </tr>

                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                </tbody>
                            </table>
                            </tr>
                            </tbody></table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            (function ($) {
                $(document).ready(function () {
                    $('.Most_product-search').select2({
                        ajax: {
                            url: ajaxurl,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term,
                                    action: 'mostgetposts'
                                };
                            },
                            processResults: function (data) {
                                var options = [];
                                if (data) {
                                    $.each(data, function (index, text) {
                                        options.push({id: text[0], text: text[1]});
                                    });
                                }
                                return {
                                    results: options
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 3,
                        language: {
                            inputTooShort: function () {
                                return '<?php _e('Please enter 3 or more characters', 'myl10n'); ?>';
                            },
                            noResults: function () {
                                return '<?php _e('No Results Found', 'myl10n'); ?>';
                            },
                            searching: function () {
                                return  '<?php _e('Searching...', 'myl10n'); ?>';
                            }
                        }
                    });
                });
            })(jQuery);
        </script>
    <?php }else {
        ?>
        <div class="wrap woocommerce">
            <h2>
                <span style="position: relative;top: 3px;"><img  src="/wp-content/plugins/woomostmarket/img/most_logo_svg.svg"  /></span>
                <?php _e('MOST Market - Coupons types', 'myl10n'); ?></h2>
            <div style="margin-top: 50px;" >
                <div id="content">
                    <form style="margin-top: 50px;" method="post" action="">
                        <div class="tab_div woocommerce" >
                            <h3 style="margin-left: 10px;"><?php _e('Settings not specified!', 'myl10n'); ?></h3>
                            <h4 style="margin-left: 10px;"><?php _e('Check the link, key and secret in the settings section', 'myl10n'); ?></h4>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
    }
}

/**
 * Сохранение настроек купонов
 */
add_action('wp_ajax_woo_most_coupon_setup_save', 'woo_most_coupon_setup_save');

function woo_most_coupon_setup_save($data) {

    $multipleValues = trim($_POST['multipleValues']);
    $tempData = str_replace("\\", "", $multipleValues);
    $multiple_arr = json_decode($tempData);
    foreach ($multiple_arr as $service => $data) {
        update_option('most_' . $service, $data, true);
    }
    echo 1;
    exit;
}

// контент страницы Отчет
function woomostmarket_report_callback() {
    $currency_symbol = get_woocommerce_currency_symbol();
    ?>
    <link rel="stylesheet" href="/wp-content/plugins/woomostmarket/css/woomostmarket.css">
    <script src="/wp-content/plugins/woomostmarket/js/woomostmarket_scripts.js"></script>
    <div class="wrap woocommerce">
        <h2>
            <span style="position: relative;top: 3px;"><img  src="/wp-content/plugins/woomostmarket/img/most_logo_svg.svg"  /></span>
            <?php _e('MOST Market - reports on used MOST Market coupons', 'myl10n'); ?></h2>
        <div style="margin-top: 50px;" >
            <div class="tab_div woocommerce" style="margin-top: 50px;" >
                <h3 style="margin-left: 10px;"><?php _e('Create report', 'myl10n'); ?></h3>
                <form style="margin-top: 50px;" method="post" action="">
                    <div class="tab_div" >
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_date_start"><?php _e('From:', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_date_start"   id="most_market_date_start" type="date" style="" value="" class="most_market_input_txt" >
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="titledesc">
                                        <label for="most_market_date_end"><?php _e('To:', 'myl10n'); ?></label>
                                    </th>
                                    <td class="forminp forminp-text">
                                        <input name="most_market_date_end" id="most_market_date_end" type="date" style="" value="" class="most_market_input_txt" >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="submit" style="margin-top: 60px; padding-left: 30px; ">
                        <input  type="submit" name="Submit" class="sbmt-btn y_save_btn y_save_btn_report" value="<?php _e('Create report', 'myl10n'); ?>" />
                    </p>
                    <div style=" height: 20px; padding-left: 30px; " class="tab_div woocommerce_report_most"  >
                        <a class="woocommerce_report_most_a" style="font-size: 20px;font-weight: bold;color: #000;display: none;" href="/wp-content/plugins/woomostmarket/files/most_market_report.xls"><?php _e('Download report', 'myl10n'); ?></a>
                    </div>
                </form>
            </div>
            <div class="tab_div woocommerce" style="margin-top: 50px;" >
                <h3 style="margin-left: 10px;"><?php _e('Last 20 orders', 'myl10n'); ?></h3>
                <table class="form-table">
                    <tbody><tr valign="top">
                            <td class="wc_payment_gateways_wrapper" colspan="2">
                                <table class="wc_gateways widefat">
                                    <tbody class="ui-sortable">
                                    <thead>
                                        <tr >
                                            <th scope="row" class="titledesc">
                                                <h4><?php _e('Date', 'myl10n'); ?></h4>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <h4><?php _e('Order ID', 'myl10n'); ?></h4>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <h4><?php _e('Coupons', 'myl10n'); ?></h4>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <h4><?php _e('Discount', 'myl10n'); ?></h4>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <h4><?php _e('Total', 'myl10n'); ?></h4>
                                            </th>
                                        </tr>

                                    </thead>
                                    <?php
                                    $my_posts = wc_get_orders(array(
                                        'numberposts' => 20,
                                        'category' => 0,
                                        'orderby' => 'date',
                                        'order' => 'DESC',
                                        'include' => array(),
                                        'exclude' => array(),
                                        'meta_key' => 'most_market_order',
                                        'meta_value' => '1',
                                        'post_status' => 'wc-processing, wc-completed',
                                        'post_type' => 'shop_order',
                                        'suppress_filters' => true
                                    ));

                                    $i = 0;

                                    foreach ($my_posts as $order) {
                                        $date = $order->get_date_created(); // объект WC_DateTime
                                        $coupon_codes = implode(",", $order->get_coupon_codes());
                                        ?>
                                        <tr <?php if ($i % 2) echo "class='second_th'"; ?>>
                                            <th scope="row" class="titledesc">
                                                <label ><?php echo $date->date('d.m.Y'); ?></label>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <label ><?php echo $order->get_id(); ?></label>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <label ><?php echo $coupon_codes; ?></label>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <label ><?php echo $order->get_discount_total() . ' ' . $currency_symbol; ?></label>
                                            </th>
                                            <th scope="row" class="titledesc">
                                                <label ><?php echo $order->get_total() . ' ' . $currency_symbol; ?></label>
                                            </th>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                    </tbody>
                </table>
                </tr>
                </tbody></table>
            </div>
        </div>
    </div>
    <script>
            (function ($) {
                $(document).ready(function () {
                    document.getElementById('most_market_date_start').valueAsDate = new Date();
                    document.getElementById('most_market_date_end').valueAsDate = new Date();
                });

            })(jQuery);

    </script>
    <?php
}

/**
 * Создание отчета за период
 */
add_action('wp_ajax_woo_most_coupon_get_report', 'woo_most_coupon_get_report');

function woo_most_coupon_get_report($data) {

    include_once ABSPATH . '/wp-content/plugins/woomostmarket/PHPExcel/Classes/PHPExcel.php';
    $most_market_date_start = trim($_POST['most_market_date_start']);
    $most_market_date_end = trim($_POST['most_market_date_end']);

    $currency_symbol = get_woocommerce_currency_symbol();

    $my_posts = wc_get_orders(array(
        'numberposts' => -1,
        'category' => 0,
        'orderby' => 'date',
        'order' => 'ASC',
        'include' => array(),
        'exclude' => array(),
        'meta_key' => 'most_market_order',
        'meta_value' => '1',
        'post_status' => 'wc-processing, wc-completed',
        'date_query' => array(
            'after' => $most_market_date_start . ' 00:00:00',
            'before' => $most_market_date_end . ' 23:59:59'
        ),
        'post_type' => 'shop_order',
        'suppress_filters' => true
    ));


    $total = 0;
    foreach ($my_posts as $order) {

        $date = $order->get_date_created(); // объект WC_DateTime
        $order_date = $date->date('d.m.Y');
        $order_id = $order->get_id();
        $coupon_codes = implode(", ", $order->get_coupon_codes());
        $order_discount = $order->get_discount_total();
        $order_total = $order->get_total();
        $total += $order_total;
        $arr_final[] = array($order_date, $order_id, $coupon_codes, $order_discount, $order_total);
    }

    $arr_final[] = array('', '', '', '', '');
    $arr_final[] = array('', '', '', __('Total for all orders: ', 'myl10n'), $total);
    $catList = array();

    foreach ($arr_final as $report) {
        $catList[] = [__('Date', 'myl10n') => (string) $report[0], __('Order ID', 'myl10n') => (string) $report[1], __('Coupons', 'myl10n') => (string) $report[2], __('Discount', 'myl10n') => (string) $report[3], __('Total', 'myl10n') => (string) $report[4]];
    }

    $document = new \PHPExcel();
    $sheet = $document->setActiveSheetIndex(0);
    $columnPosition = 0;
    $startLine = 0;
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $startLine++;
    $columns = ['', __('Date', 'myl10n'), __('Order ID', 'myl10n'), __('Coupons', 'myl10n'), __('Discount', 'myl10n'), __('Total', 'myl10n')];
    $currentColumn = $columnPosition;
    foreach ($columns as $column) {
        $sheet->getStyleByColumnAndRow($currentColumn, $startLine)
                ->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('f0f0f1');
        $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column);
        $currentColumn++;
    }

    foreach ($catList as $key => $catItem) {
        $startLine++;
        $currentColumn = $columnPosition;

        foreach ($catItem as $value) {
            $currentColumn++;
            $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $value);
        }
    }

    $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel5');
    $objWriter->save(plugin_dir_path(__FILE__) . "files/most_market_report.xls");

    echo 1;

    exit;
}

/**
 * Функция получает новые купоны и создает их в магазине
 */
add_action('woocommerce_before_cart', 'most_market_get_coupons');

add_action('woocommerce_add_to_cart', 'most_market_get_coupons', 10, 6);

function most_market_get_coupons() {
    include_once ABSPATH . 'wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETApi.php';
    include_once ABSPATH . 'wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETException.php';

    $most_market = new MOST_MARKET\MOST_MARKETApi();
    $most_market_key = get_option('most_market_key');
    $most_market_secret = get_option('most_market_secret');
    global $wpdb;

    $get_all_coupon_last_time = get_option('get_all_coupon_last_time');


    $res = $most_market->GetAllCoupon($most_market_key, $most_market_secret, $get_all_coupon_last_time);



    if ($res["result"] != null) {

        foreach ($res["result"] as $r) {

            $postid = $wpdb->get_var('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = "' . $r["key"] . '" AND post_type = "shop_coupon" ');

            if ($postid == null) {
                $coupon_code = $r["key"];
                $amount = $r["discount"]["amount"];



                $service = $r["discount"]["service"];


                $coupon = array(
                    'post_title' => $coupon_code,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_excerpt' => 'Most market',
                    'post_type' => 'shop_coupon'
                );

                $new_coupon_id = wp_insert_post($coupon);


                update_post_meta($new_coupon_id, 'coupon_amount', $amount);
               

                update_post_meta($new_coupon_id, 'exclude_product_ids', '');
                update_post_meta($new_coupon_id, 'usage_limit', 1);
                update_post_meta($new_coupon_id, 'usage_limit_per_user', 1);
                update_post_meta($new_coupon_id, 'limit_usage_to_x_items', 1);
                update_post_meta($new_coupon_id, 'expiry_date', $r["deprecation"] / 1000);
                update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
                update_post_meta($new_coupon_id, 'free_shipping', 'no');
                update_post_meta($new_coupon_id, 'most_market_coupon', 1);

                $coupon_type_arr = $most_market->GetCouponsData($most_market_key, $most_market_secret);

                if ($coupon_type_arr["result"] != null) {
                    foreach ($coupon_type_arr["result"]["discounts"] as $coupon_type) {


                        if ($service == $coupon_type["service"]) {
                            $basket = $coupon_type["basket"];
                            $type = $coupon_type["type"];
                            $basketMin = $coupon_type["basketMin"];
                            $basketMax = $coupon_type["basketMax"];
                        }
                    }
                }
                if ($basketMin > 0) {
                    update_post_meta($new_coupon_id, 'minimum_amount', $basketMin);

                     update_post_meta($new_coupon_id, 'individual_use', 'yes');
                }

                if ($basketMax > 0) {
                    update_post_meta($new_coupon_id, 'maximum_amount', $basketMax);
                     update_post_meta($new_coupon_id, 'individual_use', 'yes');
                }

                $product_ids = get_option('most_' . $r["discount"]["service"]);

                if ($product_ids && count($product_ids) > 0) {
                    $products_coupon = implode(",", $product_ids);
                    update_post_meta($new_coupon_id, 'product_ids', $products_coupon);

                    if ($type == 'fixed') {
                        $discount_type = 'fixed_product'; // Type: fixed_cart, percent, fixed_product, percent_product
                    }
                    if ($type == 'percent') {
                        $discount_type = 'percent';
                    }

                    update_post_meta($new_coupon_id, 'discount_type', $discount_type);
                } else {

                    if ($type == 'fixed' && $basket == true) {
                        $discount_type = 'fixed_cart';
                    }
                    if ($type == 'percent' && $basket == true) {
                        $discount_type = 'percent';
                    }
                    update_post_meta($new_coupon_id, 'discount_type', $discount_type);
                }
            }
        }
    }


    $dt = date('Y-m-d H:i:s', strtotime('-1 hour'));
    update_option('get_all_coupon_last_time', strtotime($dt), true);
}

/**
 * Функция отправляет факт использования купона
 */
add_action('woocommerce_order_status_processing', 'most_use_coupon');

function most_use_coupon($order_id) {
    global $wpdb;
    include_once ABSPATH . 'wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETApi.php';
    include_once ABSPATH . 'wp-content/plugins/woomostmarket/MOST_MARKET/MOST_MARKETException.php';

    $most_market = new MOST_MARKET\MOST_MARKETApi();
    $most_market_key = get_option('most_market_key');
    $most_market_secret = get_option('most_market_secret');
    $coupons = $wpdb->get_results('SELECT * FROM `wp_woocommerce_order_items` WHERE `order_id` = "' . $order_id . '" AND `order_item_type` = "coupon"');

    if ($coupons) {
        $coupons_order = array();

        foreach ($coupons as $coupon) {
            $postid = $wpdb->get_var('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = "' . $coupon->order_item_name . '" AND post_type = "shop_coupon" ');
            if ($postid != null) {
                $most_market_coupon = get_post_meta($postid, 'most_market_coupon', true);
                if ($most_market_coupon) {
                    $res = $most_market->UseCoupon($most_market_key, $most_market_secret, $coupon->order_item_name);
                    $coupons_order[] = $coupon->order_item_name;
                }
            }
        }
        if (count($coupons_order) > 0) {
            update_post_meta($order_id, 'most_market_order', 1);
            update_post_meta($order_id, 'most_market_order_coupons', $coupons_order);
        }
    }
}

/*
 *  Функция выводит колонку с примененными купонами в списке заказов
 *
 */

function woo_customer_order_coupon_column_for_orders($columns) {
    $columns['order_coupons'] = __('Coupons', 'woocommerce');
    return $columns;
}

$most_market_show_coupon = get_option('most_market_show_coupon');
if ($most_market_show_coupon == 1) {

    add_action('manage_shop_order_posts_custom_column', 'woo_display_customer_order_coupon_in_column_for_orders');
    add_filter('manage_edit-shop_order_columns', 'woo_customer_order_coupon_column_for_orders');
}

function woo_display_customer_order_coupon_in_column_for_orders($column) {
    global $the_order, $post, $wpdb;
    if ($column == 'order_coupons') {
        if ($coupons = $the_order->get_coupon_codes()) {
            $srt = '';
            foreach ($coupons as $coupon) {
                $postid = $wpdb->get_var('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = "' . $coupon . '" AND post_type = "shop_coupon" ');
                if ($postid != null) {
                    $most_market_coupon = get_post_meta($postid, 'most_market_coupon', true);
                    if ($most_market_coupon) {
                        $srt .= $coupon . '(MOST),';
                    } else {
                        $srt .= $coupon . ', ';
                    }
                } else {
                    $srt .= $coupon . ',';
                }
            }
            $result = substr($srt, 0, -2);
            echo $result . ' (' . count($coupons) . ')';
        } else {
            echo '<small><em></em></small>';
        }
    }
}

function filter_woocommerce_product_data_tabs($default_tabs) {
    $default_tabs['custom_tab'] = array(
        'label' => __('Custom Tab', 'woocommerce'),
        'target' => 'stock_sync_data_tab',
        'priority' => 80,
        'class' => array()
    );

    return $default_tabs;
}

/*
 *  Поиск товаров для страницы настроек связи с купонами
 *
 */

add_action('wp_ajax_mostgetposts', 'mostgetposts_get_posts_ajax_callback');

function mostgetposts_get_posts_ajax_callback() {

    $return = array();

    $search_results = new WP_Query(array(
        's' => $_GET['q'],
        'post_status' => 'publish',
        'post_type' => 'product',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 50
    ));
    if ($search_results->have_posts()) :
        while ($search_results->have_posts()) : $search_results->the_post();
            $product = wc_get_product($search_results->post->ID);
            $title = esc_html(wp_strip_all_tags($product->get_formatted_name()));
            $return[] = array($search_results->post->ID, $title);
        endwhile;
    endif;
    echo json_encode($return);
    die;
}

/*
 *  Получение новых купонов по cron
 *
 */

add_filter('cron_schedules', 'true_five_min_interval');

function true_five_min_interval($raspisanie) {

    $raspisanie['most_5_min'] = array(
        'interval' => 300,
        'display' => 'Каждые 5 минут'
    );
    return $raspisanie;
}

if (!wp_next_scheduled('most_market_get_coupons_cron')) {
    wp_schedule_event(time(), 'most_5_min', 'most_market_get_coupons_cron', array());
}
add_action('most_market_get_coupons_cron', 'most_market_get_coupons');

//wp_clear_scheduled_hook( 'most_market_get_coupons_cron' ); // удалиить задание из cron

