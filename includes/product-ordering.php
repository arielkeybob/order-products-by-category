<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Adiciona a função custom_order_by_order_value ao gancho de ação pre_get_posts
add_action('pre_get_posts', 'custom_order_by_order_value');

function custom_order_by_order_value($query) {
    // Verificar se a ordenação está ativada
    $ordering_enabled = get_option('opbycat_enable_ordering') === 'yes';

    if (!is_admin() && $query->is_main_query() && is_product_category() && $ordering_enabled) {
        $category_id = get_queried_object_id();
        $product_ordering = get_term_meta($category_id, 'product_ordering', true);

        if ($product_ordering === 'order_value') {
            // Ordenar por order_value
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', 'order_in_category_' . $category_id);
            $query->set('order', 'ASC');
            $query->set('meta_query', array(
                'relation' => 'OR',
                array(
                    'key' => 'order_in_category_' . $category_id,
                    'compare' => 'EXISTS',
                ),
                array(
                    'key' => 'order_in_category_' . $category_id,
                    'compare' => 'NOT EXISTS',
                    'value' => '0',
                ),
            ));
        } elseif ($product_ordering === 'title') {
            // Ordenar por título
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
        }
        // A opção "default" mantém a ordenação padrão do WordPress
    }
}
