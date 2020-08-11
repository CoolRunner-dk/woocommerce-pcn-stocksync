<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('WC_Settings_PCNStockSync')) {

    function pcnstocksync_add_coolrunner_settings() {

        class WC_Settings_PCNStockSync extends WC_Settings_Page
        {
            public function __construct() {
                $this->id = 'pcnstocksync';
                $this->label = __('PCN StockSync - Settings', 'coolrunner-pcn-stocksync');

                add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
                add_action('woocommerce_settings_' . $this->id, array($this, 'output'));

                add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
                add_action('woocommerce_get_settings_for_' . $this->id, array($this, 'get_option'));
                add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
            }

            // Get settings array - Returns all input fields
            public function get_settings($current_section = '') {
                $menu = array(
                    array(
                        'name' => __('PCN StockSync - Settings', 'coolrunner-pcn-stocksync'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'pcnstocksync_settings',
                    ),
                    array(
                        'name' => 'PCN API Endpoint',
                        'type' => 'text',
                        'id' => 'pcn_settings_apiendpoint',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN API Endpoint (https://xx.xx.dk/rest/v6/api.php)', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('PCN Base-Auth Username', 'coolrunner-pcn-stocksync'),
                        'type' => 'text',
                        'id' => 'pcn_settings_baseauthusername',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN Base-Auth username', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('PCN Base-Auth Password', 'coolrunner-pcn-stocksync'),
                        'type' => 'password',
                        'id' => 'pcn_settings_baseauthpassword',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN Base-Auth password (ex: xx1000)', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('PCN OLS Customer ID', 'coolrunner-pcn-stocksync'),
                        'type' => 'text',
                        'id' => 'pcn_settings_olsuserid',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN OLS Customer ID (ex: 88)', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('PCN OLS Customer Username', 'coolrunner-pcn-stocksync'),
                        'type' => 'text',
                        'id' => 'pcn_settings_olsusername',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN OLS Customer Username (ex: xx10)', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('PCN OLS Customer Password', 'coolrunner-pcn-stocksync'),
                        'type' => 'password',
                        'id' => 'pcn_settings_olspassword',
                        'desc_tip' => true,
                        'desc' => __('Fill your PCN OLS Customer Password (ex: x1000)', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('Get stock quantity at new order placed?', 'coolrunner-pcn-stocksync'),
                        'type' => 'checkbox',
                        'id' => 'pcn_settings_updateonorder',
                        'desc_tip' => false,
                        'desc' => __('Get stock quantity when ever a new order is placed', 'coolrunner-pcn-stocksync'),
                    ),
                    array(
                        'name' => __('Update stock from PCN every 3. hours', 'coolrunner-pcn-stocksync'),
                        'type' => 'checkbox',
                        'id' => 'pcn_settings_updatecron',
                        'desc_tip' => false,
                        'desc' => __('Get stock every 3. hours from PCN', 'coolrunner-pcn-stocksync'),
                    )
                );

                $settings = apply_filters('pcnstocksync_settings', $menu);
                return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
            }

            // Save settings
            public function save()
            {
                parent::save();
            }
        }

        return new WC_Settings_PCNStockSync();
    }

    add_filter('woocommerce_get_settings_pages', 'pcnstocksync_add_coolrunner_settings', 16);

}

