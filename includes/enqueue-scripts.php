<?php
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Check if the current page is the WooCommerce product category edit page
function is_woocommerce_edit_product_category_page() {
    global $pagenow, $typenow;

    // Check if it's a product edit page in WooCommerce
    if ( $typenow === 'product' && ($pagenow === 'edit-tags.php' || $pagenow === 'term.php') ) {
        if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] === 'product_cat' ) {
            return true;
        }
    }

    return false;
}

// Enqueue scripts specific to the order category functionality
function enqueue_order_category_scripts() {
    if ( ! is_woocommerce_edit_product_category_page() ) {
        return;
    }

    wp_enqueue_script(
        'order-category-script',
        ORDER_PRODUCTS_BY_CATEGORY_URL . 'admin/js/order-category-script.js',
        array('jquery', 'jquery-ui-sortable'),
        PLUGIN_VERSION,
        true
    );
}

// Enqueue styles specific to the order category functionality
function enqueue_order_category_styles() {
    if ( ! is_woocommerce_edit_product_category_page() ) {
        return;
    }

    wp_enqueue_style(
        'order-category-style',
        ORDER_PRODUCTS_BY_CATEGORY_URL . 'admin/css/order-category-style.css',
        array(),
        PLUGIN_VERSION
    );
}

// Hook into the admin_enqueue_scripts action to load the custom scripts and styles
add_action( 'admin_enqueue_scripts', 'enqueue_order_category_scripts' );
add_action( 'admin_enqueue_scripts', 'enqueue_order_category_styles' );
