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
                $this->label = 'PakkecenterNord - Indstillinger';

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
                        'name' => 'PCN WooCommerce Lagersynkronisering - Indstillinger',
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'pcnstocksync_settings',
                    ),
                    array(
                        'name' => 'PCN API Endpoint',
                        'type' => 'text',
                        'id' => 'pcn_settings_apiendpoint',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN API Endpoint (eks: https://xx.xx.dk/rest/v6/api.php)',
                    ),
                    array(
                        'name' => 'PCN Base-Auth Brugernavn',
                        'type' => 'text',
                        'id' => 'pcn_settings_baseauthusername',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN Base-Auth Brugeravn (eks: xxx1000)',
                    ),
                    array(
                        'name' => 'PCN Base-Auth Kodeord',
                        'type' => 'password',
                        'id' => 'pcn_settings_baseauthpassword',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN Base-Auth kodeord (eks: xx1000)',
                    ),
                    array(
                        'name' => 'PCN OLS Kunde ID',
                        'type' => 'text',
                        'id' => 'pcn_settings_olsuserid',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN OLS Kunde ID (eks: 88)',
                    ),
                    array(
                        'name' => 'PCN OLS Kunde Brugernavn',
                        'type' => 'text',
                        'id' => 'pcn_settings_olsusername',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN OLS Kunde Brugernavn (eks: xx10)',
                    ),
                    array(
                        'name' => 'PCN OLS Kunde Kodeord',
                        'type' => 'password',
                        'id' => 'pcn_settings_olspassword',
                        'desc_tip' => true,
                        'desc' => 'Indtast dit PCN OLS Kunde Kodeord (eks: x1000)',
                    ),
                    array(
                        'name' => 'Hent lagerantal ved ny ordre?',
                        'type' => 'checkbox',
                        'id' => 'pcn_settings_updateonorder',
                        'desc_tip' => false,
                        'desc' => 'Hent lagerantal ved hver ordre der er gennemført.',
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

