<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.0
Author: Ariel
*/

// Variável global para armazenar o valor de ordenação
global $order_value;

// Adicione um campo personalizado na página de edição de categoria para exibir produtos
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20); // Prioridade 20

function display_category_products_field($term) {
    $category_id = $term->term_id;
    $products = get_category_products($category_id);

    echo '<tr class="form-field">';
    echo '<th scope="row">Produtos na Categoria</th>';
    echo '<td>';
    
    if (empty($products)) {
        echo 'Nenhum produto nesta categoria.';
    } else {
        echo '<ul>';
        foreach ($products as $product) {
            $product_id = $product->ID;
            $order_value = get_post_meta($product_id, 'order_in_category_' . $category_id, true);

            echo '<li>';
            echo '<a href="' . get_edit_post_link($product_id) . '">' . $product->post_title . '</a>';
            echo '<input type="number" name="order_in_category[' . $product_id . ']" value="' . esc_attr($order_value) . '" step="1">';
            echo '</li>';
        }
        echo '</ul>';
    }
    
    echo '</td>';
    echo '</tr>';
}




// Salva os valores do campo numérico personalizado dos produtos associados a essa categoria
add_action('edited_product_cat', 'save_category_products_order', 10, 2); // Prioridade 10

function save_category_products_order($term_id, $tt_id) {
    if (isset($_POST['order_in_category'])) {
        foreach ($_POST['order_in_category'] as $product_id => $order_value) {
            update_post_meta($product_id, 'order_in_category_' . $term_id, $order_value);
        }
    }
}

// Função de depuração para mostrar os valores dos campos no console na página de frontend
/*
function display_category_meta_tags() {
    if (is_product_category()) {
        $category = get_queried_object();
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
*/

// Adicione a ação para chamar a função de depuração na página de frontend
add_action('wp_footer', 'display_category_meta_tags', 20); // Prioridade 20

// Adicione a função custom_order_by_plugin_field ao seu plugin com prioridade 20
function custom_order_by_plugin_field($clauses, $query) {
    if (is_product_category()) {
        global $wpdb, $order_value;

        // Verifique se $order_value está definida
        if (isset($order_value)) {
            $orderby = "ORDER BY $order_value+0 ASC"; // +0 para garantir que seja tratado como um número
            $clauses['orderby'] = $orderby;

            // Remova a ordenação por 'menu_order' e qualquer outra ordenação padrão
            $clauses['orderby'] = '';
        }
    }

    return $clauses;
}


// Obtém a lista de produtos pertencentes a uma categoria
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

//Shortcode que mostra a ordem de um produto em determinada categoria
/*
function display_order_value_shortcode($atts) {
    $post_id = get_the_ID();
    $category_id = get_queried_object_id(); // Obtém o ID da categoria atual
    $order_value = get_post_meta($post_id, 'order_in_category_' . $category_id, true);

    return  $order_value;
}
add_shortcode('order_value', 'display_order_value_shortcode');
*/


//Order products by order_in_category_
function custom_order_by_order_value($query) {
    if (is_product_category()) {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'order_in_category_' . get_queried_object_id());
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'custom_order_by_order_value');
