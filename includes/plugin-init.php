<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Other unchanged PHP code snippets
// Debug function to display field values in the console on the frontend page
add_action('wp_footer', 'display_category_meta_tags', 20); // Priority 20
