<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Adiciona a função custom_order_by_order_value ao gancho de ação pre_get_posts
add_action('pre_get_posts', 'custom_order_by_order_value');

function custom_order_by_order_value($query) {
    // Apenas alterar a consulta principal no front-end e para categorias de produto
    if (!is_admin() && $query->is_main_query() && is_product_category()) {
        $ordering_enabled = get_option('opbycat_enable_ordering') === 'yes';

        // Continuar apenas se a ordenação estiver ativada
        if ($ordering_enabled) {
            $category_id = get_queried_object_id();
            $product_ordering = get_term_meta($category_id, 'product_ordering', true);

            switch ($product_ordering) {
                case 'order_value':
                    // Ordenar por um valor meta personalizado
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
                    break;
                case 'title':
                    // Ordenar por título
                    $query->set('orderby', 'title');
                    $query->set('order', 'ASC');
                    break;
                default:
                    // Deixa a ordenação padrão do WordPress (não faz alterações)
                    break;
            }
        }
    }
}
add_action('pre_get_posts', 'custom_order_by_order_value');

