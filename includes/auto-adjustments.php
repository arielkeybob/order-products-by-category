<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Automatically set order value when a post is saved
add_action('save_post', 'auto_set_order_value');

function auto_set_order_value($post_id) {
    // ... (content of auto_set_order_value function)
}
