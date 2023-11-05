<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


function get_category_products($category_id) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'order_in_category_' . $category_id,
                'compare' => 'EXISTS',
            ),
            array(
                'key'     => 'order_in_category_' . $category_id,
                'compare' => 'NOT EXISTS',
                'value'   => '' // Isso é apenas um placeholder.
            ),
        ),
    );

    $posts = get_posts($args);

    // Ordenar os posts manualmente.
    usort($posts, function ($a, $b) use ($category_id) {
        $orderA = (int) get_post_meta($a->ID, 'order_in_category_' . $category_id, true);
        $orderB = (int) get_post_meta($b->ID, 'order_in_category_' . $category_id, true);

        if ($orderA === $orderB) {
            return 0;
        }

        return ($orderA < $orderB) ? -1 : 1;
    });

    return $posts;
}

