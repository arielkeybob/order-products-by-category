<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Gets the list of products belonging to a category
function get_category_products($category_id) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $category_id,
                'operator' => 'IN',
            ),
        ),
    );

    return get_posts($args);
}
