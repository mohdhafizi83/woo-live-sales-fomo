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
    exit; // Keselamatan: Halang akses terus
}

function wlsf_init() {
    // KESELAMATAN: Pastikan WooCommerce sedang aktif
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Suntik pop-up ke bahagian footer muka depan (frontend)
    add_action( 'wp_footer', 'wlsf_display_fomo_popup' );
    // Muatkan fail JS
    add_action( 'wp_enqueue_scripts', 'wlsf_enqueue_scripts' );
}
add_action( 'plugins_loaded', 'wlsf_init' );

// 1. Fungsi Teras: Tarik data pesanan dengan Cache (Transient)
function wlsf_get_recent_orders_data() {
    $transient_key = 'wlsf_recent_orders_cache';
    $orders_data   = get_transient( $transient_key );

    // Jika tiada cache, jalankan query WooCommerce
    if ( false === $orders_data ) {
        $orders_data = array();
        
        // Dapatkan 5 pesanan 'completed' atau 'processing' yang terbaru
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
                    // Ambil item pertama sahaja dalam pesanan tersebut
                    $item       = reset( $items ); 
                    $product    = $item->get_product();
                    
                    if ( $product ) {
                        // KESELAMATAN: Sanitasi bandar pembeli
                        $city = sanitize_text_field( $order->get_billing_city() );
                        if ( empty( $city ) ) $city = 'Someone'; // Fallback jika tiada bandar

                        $orders_data[] = array(
                            'city'         => $city,
                            'product_name' => $product->get_name(), // Tak perlu esc_html di sini, kita buat masa paparan di JS
                            'url'          => $product->get_permalink(),
                            'image'        => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ?: wc_placeholder_img_src(),
                            'time_ago'     => human_time_diff( $order->get_date_created()->getOffsetTimestamp(), current_time( 'timestamp' ) ) . ' ago',
                        );
                    }
                }
            }
        }
        
        // PRESTASI: Simpan dalam cache selama 30 minit
        set_transient( $transient_key, $orders_data, 30 * MINUTE_IN_SECONDS );
    }

    return $orders_data;
}

// 2. Daftar JS dan hantar data kepadanya
function wlsf_enqueue_scripts() {
    // Jangan muatkan di halaman checkout atau cart untuk elak gangguan
    if ( is_cart() || is_checkout() ) {
        return;
    }

    $orders_data = wlsf_get_recent_orders_data();

    // Jika tiada jualan, jangan bazirkan masa muat turun JS
    if ( empty( $orders_data ) ) return;

    wp_enqueue_script( 'wlsf-fomo-js', plugin_dir_url( __FILE__ ) . 'assets/fomo.js', array(), '1.0.0', true );
    
    // KESELAMATAN: Hantar data tatasusunan (array) ke JavaScript dengan selamat
    wp_localize_script( 'wlsf-fomo-js', 'wlsfData', array(
        'orders' => $orders_data
    ) );
}

// 3. Bina Struktur HTML asas & CSS (Disuntik ke wp_footer)
function wlsf_display_fomo_popup() {
    if ( is_cart() || is_checkout() ) return;
    $orders_data = wlsf_get_recent_orders_data();
    if ( empty( $orders_data ) ) return;
    ?>
    <style>
        .wlsf-popup-container {
            position: fixed;
            bottom: -150px; /* Sembunyi ke bawah pada awalnya */
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
            transition: bottom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Efek melantun sikit */
            font-family: sans-serif;
            border: 1px solid #eee;
        }
        .wlsf-popup-container.wlsf-show {
            bottom: 20px; /* Naik ke atas */
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