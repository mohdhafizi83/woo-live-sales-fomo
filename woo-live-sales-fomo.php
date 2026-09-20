<?php
/**
 * Plugin Name:       Woo Live Sales FOMO Notification
 * Plugin URI:        https://github.com/mohdhafizi83/woo-live-sales-fomo
 * Description:       Boost conversions with real-time sales notifications, highly optimized using Transient caching.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Tested up to:      6.9.4
 * Requires PHP:      7.4
 * WC requires at least: 8.0
 * WC tested up to:   8.7
 * Author:            Mohd Hafizi
 * Author URI:        https://github.com/mohdhafizi83
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       woo-live-sales-fomo
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Security: block direct access
}

function wlsf_init() {
    // Security: make sure WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Inject the popup into the frontend footer.
    add_action( 'wp_footer', 'wlsf_display_fomo_popup' );
    // Load the JS file.
    add_action( 'wp_enqueue_scripts', 'wlsf_enqueue_scripts' );
}
add_action( 'plugins_loaded', 'wlsf_init' );

/**
 * 1. Core function: pull recent order data with Transient caching.
 */
function wlsf_get_recent_orders_data() {
    $transient_key = 'wlsf_recent_orders_cache';
    $orders_data   = get_transient( $transient_key );

    // If there is no cache, run the WooCommerce query.
    if ( false === $orders_data ) {
        $orders_data = array();

        // Get the 5 most recent 'completed' or 'processing' orders.
        $orders = wc_get_orders( array(
            'limit'   => 5,
            'status'  => array( 'wc-completed', 'wc-processing' ),
            'orderby' => 'date',
            'order'   => 'DESC',
        ) );

        if ( ! empty( $orders ) ) {
            foreach ( $orders as $order ) {
                $items = $order->get_items();
                if ( ! empty( $items ) ) {
                    // Take only the first item in the order.
                    $item    = reset( $items );
                    $product = $item->get_product();

                    if ( $product ) {
                        // Security: sanitize the buyer's city.
                        $city = sanitize_text_field( $order->get_billing_city() );
                        if ( empty( $city ) ) {
                            $city = 'Someone'; // Fallback when no city is set.
                        }

                        $orders_data[] = array(
                            'city'         => $city,
                            'product_name' => $product->get_name(), // Escaped at render time in JS via textContent.
                            'url'          => $product->get_permalink(),
                            'image'        => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ?: wc_placeholder_img_src(),
                            'time_ago'     => human_time_diff( $order->get_date_created()->getOffsetTimestamp(), current_time( 'timestamp' ) ) . ' ago',
                        );
                    }
                }
            }
        }

        // Performance: cache for 30 minutes.
        set_transient( $transient_key, $orders_data, 30 * MINUTE_IN_SECONDS );
    }

    return $orders_data;
}

/**
 * 2. Register the JS and pass data to it.
 */
function wlsf_enqueue_scripts() {
    // Don't load on cart/checkout pages to avoid distraction.
    if ( is_cart() || is_checkout() ) {
        return;
    }

    $orders_data = wlsf_get_recent_orders_data();

    // If there are no sales, don't waste bandwidth downloading JS.
    if ( empty( $orders_data ) ) {
        return;
    }

    wp_enqueue_script( 'wlsf-fomo-js', plugin_dir_url( __FILE__ ) . 'assets/fomo.js', array(), '1.0.0', true );

    // Security: pass the array data to JavaScript safely.
    wp_localize_script( 'wlsf-fomo-js', 'wlsfData', array(
        'orders' => $orders_data
    ) );
}

/**
 * 3. Build the base HTML structure & CSS (injected into wp_footer).
 */
function wlsf_display_fomo_popup() {
    if ( is_cart() || is_checkout() ) {
        return;
    }
    $orders_data = wlsf_get_recent_orders_data();
    if ( empty( $orders_data ) ) {
        return;
    }
    ?>
    <style>
        .wlsf-popup-container {
            position: fixed;
            bottom: -150px; /* Hidden below the viewport initially */
            left: 20px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            width: 320px;
            z-index: 999999;
            transition: bottom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Slight bounce effect */
            font-family: sans-serif;
            border: 1px solid #eee;
        }
        .wlsf-popup-container.wlsf-show {
            bottom: 20px; /* Slide up into view */
        }
        .wlsf-popup-img img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .wlsf-popup-content { font-size: 13px; line-height: 1.4; }
        .wlsf-popup-content p { margin: 0; color: #555; }
        .wlsf-popup-content a { color: #000; font-weight: bold; text-decoration: none; }
        .wlsf-popup-content small { color: #999; font-size: 11px; }
    </style>

    <div id="wlsf-fomo-popup" class="wlsf-popup-container">
        <div class="wlsf-popup-img">
            <img id="wlsf-img" src="" alt="Product">
        </div>
        <div class="wlsf-popup-content">
            <p><span id="wlsf-city"></span> just bought</p>
            <a href="#" id="wlsf-url"><span id="wlsf-product"></span></a><br>
            <small id="wlsf-time"></small>
        </div>
    </div>
    <?php
}
