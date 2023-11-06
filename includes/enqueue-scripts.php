<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function enqueue_order_category_scripts_and_styles() {
    wp_enqueue_script('order-category-script', ORDER_PRODUCTS_BY_CATEGORY_URL . 'admin/js/script.js', array('jquery', 'jquery-ui-sortable'), '1.0.5', true);
    wp_enqueue_style('order-category-style', ORDER_PRODUCTS_BY_CATEGORY_URL . 'admin/css/style.css', array(), '1.0.5');
}

add_action('admin_enqueue_scripts', 'enqueue_order_category_scripts_and_styles');
