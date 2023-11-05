<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Add the function custom_order_by_plugin_field to your plugin with priority 20
function custom_order_by_plugin_field($clauses, $query) {
    // ... (content of custom_order_by_plugin_field function)
}

// Add the custom_order_by_order_value function to pre_get_posts action hook
add_action('pre_get_posts', 'custom_order_by_order_value');

function custom_order_by_order_value($query) {
    // ... (content of custom_order_by_order_value function)
}
