<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.1
Author: Ariel
*/
function enqueue_custom_scripts_and_styles() {
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'custom.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
    wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'custom.css', array(), '1.0');
}

add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_and_styles');

// Variável global para armazenar o valor de ordenação
global $order_value;

// Adicione um campo personalizado na página de edição de categoria para exibir produtos
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20); // Prioridade 20

function display_category_products_field($term) {
    $category_id = $term->term_id;
    $products = get_category_products($category_id);

    // Obtenha a opção de ordenação salva
    $product_ordering = get_term_meta($term->term_id, 'product_ordering', true);

    // Exiba o campo de seleção para ordenação
    echo '<tr class="form-field">';
    echo '<th scope="row">Produtos na Categoria</th>';
    echo '<td>';

    // Campo de seleção para a ordenação
    echo '<label for="product_ordering">Ordenação:</label>';
    echo '<select id="product_ordering" name="product_ordering">';
    echo '<option value="title" ' . selected('title', $product_ordering, false) . '>Por Título</option>';
    echo '<option value="order_value" ' . selected('order_value', $product_ordering, false) . '>Por Order Value</option>';
    echo '</select>';

    if (empty($products)) {
        echo 'Nenhum produto nesta categoria.';
    } else {
        echo '<ul id="sortable-list">';
        foreach ($products as $product) {
            $product_id = $product->ID;
            $order_value = get_post_meta($product_id, 'order_in_category_' . $category_id, true);
            if (empty($order_value)) {
                $order_value = 0;
            }

            echo '<li>';
            echo '<a href="' . get_edit_post_link($product_id) . '">' . $product->post_title . '</a>';
            echo '<input type="hidden" class="hidden-field" name="order_in_category[' . $product_id . ']" value="' . esc_attr($order_value) . '">';
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

    // Salvar a opção de ordenação selecionada
    $product_ordering = isset($_POST['product_ordering']) ? sanitize_text_field($_POST['product_ordering']) : 'title';
    update_term_meta($term_id, 'product_ordering', $product_ordering);
}



// Outros trechos do código PHP permanecem inalterados

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

// Adicione a ação para chamar a função de depuração na página de frontend
add_action('wp_footer', 'display_category_meta_tags', 20); // Prioridade 20

// Adicione a função custom_order_by_plugin_field ao seu plugin com prioridade 20
function custom_order_by_plugin_field($clauses, $query) {
    if (is_product_category()) {
        global $wpdb, $order_value;

        $term_id = get_queried_object_id();
        $product_ordering = get_term_meta($term_id, 'product_ordering', true);

        if ($product_ordering === 'order_value') {
            // Ordenar por order value
            $orderby = "ORDER BY $order_value+0 ASC";
            $clauses['orderby'] = $orderby;
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

// Order products by order_in_category_
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
add_action('pre_get_posts', 'custom_order_by_order_value');



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

add_action('save_post', 'auto_set_order_value');
