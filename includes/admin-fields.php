<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Add a custom field to the category edit page to display products
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20); // Priority 20

function display_category_products_field($term) {
    // ... (content of display_category_products_field function)
}

// Save the custom numeric field values of products associated with this category
add_action('edited_product_cat', 'save_category_products_order', 10, 2); // Priority 10

function save_category_products_order($term_id, $tt_id) {
    // ... (content of save_category_products_order function)
}
