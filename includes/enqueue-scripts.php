<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


function enqueue_custom_scripts_and_styles() {
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'admin/js/script.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
    wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'admin/css/style.css', array(), '1.0');
}

add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_and_styles');
