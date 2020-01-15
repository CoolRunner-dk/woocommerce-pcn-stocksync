<?php

// Action to update quantity when a new customer hits "Thank you" page
add_action('woocommerce_thankyou', 'pcn_stock_sync_on_order', 11, 1);
function pcn_stock_sync_on_order( $order_id ) {
    if(get_option('pcn_settings_updateonorder') == 'yes') {
        // Get order and order items
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();

        // Get stocklist by performing cURL to PCN
        $curl = new PCNStockSync_Curl();
        $stockList = $curl->getStockList();

        foreach ($order_items as $order_item) {
            $product = wc_get_product($order_item->get_data()['product_id']);

            foreach ($stockList->results as $stockItem) {
                // Check if product with same SKU exists at PCN
                if ($stockItem->articleno == $product->get_sku()) {
                    // Check if stock quantity isn't the same in WooCommerce as stock at PCN
                    if ($stockItem->instock != $product->get_stock_quantity()) {
                        // Set quantity and save product if amount isn't same as PCN
                        $product->set_stock_quantity($stockItem->instock);
                        $product->save();
                    }
                }
            }
        }
    }
}

add_action( 'wp_ajax_pcn_stock_sync_updateajax', 'pcn_stock_sync_updateajax' );
function pcn_stock_sync_updateajax() {
    $product_id = $_POST['id'];

    // Get stocklist by performing cURL to PCN
    $curl = new PCNStockSync_Curl();
    $stockList = $curl->getStockList();

    $product = wc_get_product( $product_id );
    $productFound = 0;
    $toPrint = "noChange";

    foreach ($stockList->results as $stockItem) {
        // Check if product with same SKU exists at PCN
        if($stockItem->articleno == $product->get_sku()) {
            // Check if stock quantity isn't the same in WooCommerce as stock at PCN
            if($stockItem->instock != $product->get_stock_quantity()) {
                // Set quantity and save product if amount isn't same as PCN
                $product->set_stock_quantity($stockItem->instock);
                $product->save();

                $toPrint = $stockItem->instock;
            }
            $productFound = 1;
        }
    }

    if($productFound == 0) {
        $toPrint = "notFound";
    }

    echo $toPrint;
    wp_die();
}

// Add button to sync stock from PCN
add_action('admin_footer', 'pcn_stock_sync_updatebutton');
function pcn_stock_sync_updatebutton() {
    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            var newButton = $("<div style='width: 100%; border-top: 1px solid #eeeeee; padding: 10px;'><a data-id='<?php echo get_the_ID(); ?>' id='pcn-stocksync-button' class='button button-primary button-large'>Hent lagerantal fra PakkecenterNord</a></div>");
            jQuery('.stock_fields').append(newButton)
        });

        jQuery(document).ready(function () {
            console.log('PCN StockSync - Loaded');

            jQuery("#pcn-stocksync-button").click(function () {
                jQuery(this).removeClass('button-primary').addClass('button-disabled');
                var id = jQuery(this).data('id');
                var data = {
                    'action': 'pcn_stock_sync_updateajax',
                    'id': id
                }

                jQuery.post(ajaxurl, data, function (response) {
                    if(response !== 'noChange' && response !== 'notFound') {
                        jQuery('#_stock').val(response);
                    } else {
                        if(response === 'notFound') {
                            alert('Varenummeret findes ikke hos PakkecenterNord.');
                        } else {
                            alert('Lagerantallet stemmer allerede overens.');
                        }
                    }

                    jQuery("#pcn-stocksync-button").removeClass('button-disabled').addClass('button-primary');
                })
            });
        });

    </script>
    <?php
}