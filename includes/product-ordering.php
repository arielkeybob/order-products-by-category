<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Add the function custom_order_by_plugin_field to your plugin with priority 20
function custom_order_by_plugin_field($clauses, $query) {
    if (is_product_category()) {
        error_log('Custom Order Debug: Inside custom_order_by_plugin_field'); // Mensagem de depuração
        global $wpdb, $order_value;

        $term_id = get_queried_object_id();
        $product_ordering = get_term_meta($term_id, 'product_ordering', true);

        error_log('Custom Order Debug: Product Ordering: ' . $product_ordering); // Mensagem de depuração

        if ($product_ordering === 'order_value') {
            // Ordenar por order value
            $orderby = "ORDER BY $order_value+0 ASC";
            $clauses['orderby'] = $orderby;
        }
    }

    return $clauses;
}

// Add the custom_order_by_order_value function to pre_get_posts action hook
add_action('pre_get_posts', 'custom_order_by_order_value');

function custom_order_by_order_value($query) {
    if (is_product_category()) {
        $category_id = get_queried_object_id();
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
                'value' => 0,
            ),
        ));
    }
}
