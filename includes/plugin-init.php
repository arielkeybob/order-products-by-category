<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Função de depuração para mostrar os valores dos campos no console na página de frontend
function display_category_meta_tags() {
    if (is_product_category()) {
        $category = get_queried_object();

        if ($category) {
            $category_id = $category->term_id;
            $products = get_category_products($category_id);

            if (!empty($products)) {
                echo '<script>';
                foreach ($products as $product) {
                    $product_id = $product->ID;
                    $order_value = get_post_meta($product_id, 'order_in_category_' . $category_id, true);
                    $menu_order = get_post_field('menu_order', $product_id); // Adicionando o valor de menu_order

                    echo 'console.log("Product ID: ' . $product_id . ', Order Value: ' . $order_value . ', Menu Order: ' . $menu_order . '");';
                }
                echo '</script>';
            }
        }
    }
}
// Other unchanged PHP code snippets
// Debug function to display field values in the console on the frontend page
add_action('wp_footer', 'display_category_meta_tags', 20); // Priority 20
