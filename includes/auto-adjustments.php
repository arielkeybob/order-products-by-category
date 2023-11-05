<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Automatically set order value when a post is saved
add_action('save_post', 'auto_set_order_value');

function auto_set_order_value($post_id) {
     // Verifique se o post é um produto
     if (get_post_type($post_id) === 'product') {
        // Obtenha todas as categorias do produto
        $categories = wp_get_post_terms($post_id, 'product_cat', array('fields' => 'ids'));
        
        // Verifique cada categoria e defina o valor 0 se não estiver definido
        foreach ($categories as $category_id) {
            $order_value = get_post_meta($post_id, 'order_in_category_' . $category_id, true);
            if (empty($order_value)) {
                update_post_meta($post_id, 'order_in_category_' . $category_id, 0);
            }
        }
    }
}
