<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Saída se acessado diretamente.
}

// Função de depuração para mostrar os valores dos campos no console na página de frontend
function display_category_meta_tags() {
    if (is_product_category() && defined('WP_DEBUG') && WP_DEBUG) { // Verifica se está em modo de depuração
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

                    // Usando esc_js para garantir que os valores sejam seguros para uso em JavaScript
                    echo 'console.log("Product ID: ' . esc_js($product_id) . ', Order Value: ' . esc_js($order_value) . ', Menu Order: ' . esc_js($menu_order) . '");';
                }
                echo '</script>';
            }
        }
    }
}
// Outros trechos de código PHP inalterados
add_action('wp_footer', 'display_category_meta_tags', 20); // Prioridade 20
