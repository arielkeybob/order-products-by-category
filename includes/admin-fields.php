<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Adicionar um campo personalizado à página de edição de categorias para exibir produtos
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20); // Prioridade 20

function display_category_products_field($term) {
    // Verificar se a ordenação está ativada
    $ordering_enabled = get_option('opbycat_enable_ordering') === 'yes';

    // Se a ordenação não estiver ativada, não exibir os campos
    if (!$ordering_enabled) {
        echo '<tr class="form-field"><th scope="row" valign="top"><label>A ordenação personalizada está desativada.</label></th></tr>';
        return;
    }

    // Nonce field para validação
    wp_nonce_field('save_category_products_order', 'category_products_order_nonce');

    $category_id = $term->term_id;
    $products = get_category_products($category_id);
    $product_ordering = get_term_meta($category_id, 'product_ordering', true);

    // Exibir o campo de seleção para ordenação
    echo '<tr class="form-field">';
    echo '<th scope="row" valign="top"><label for="product_ordering">Ordenar por:</label></th>';
    echo '<td>';
    echo '<select id="product_ordering" name="product_ordering" class="postform">';
    echo '<option value="default" ' . selected('default', $product_ordering, !$product_ordering) . '>Padrão</option>';
    echo '<option value="title" ' . selected('title', $product_ordering, false) . '>Título</option>';
    echo '<option value="order_value" ' . selected('order_value', $product_ordering, false) . '>Ordem Personalizada</option>';
    echo '</select>';

    // Lista de produtos
    if (!empty($products)) {
        echo '<ul id="sortable-list">';
        foreach ($products as $product) {
            $product_id = $product->ID;
            $order_value = get_post_meta($product_id, 'order_in_category_' . $category_id, true);
            $order_value = $order_value !== '' ? $order_value : '0'; // Atribuir '0' se estiver vazio
            echo '<li class="ui-state-default">';
            echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'; // Ícone de arrastar
            echo esc_html($product->post_title);
            echo '<input type="hidden" class="hidden-field" name="order_in_category[' . esc_attr($product_id) . ']" value="' . esc_attr($order_value) . '">';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo 'Nenhum produto nesta categoria.';
    }

    echo '</td></tr>';
}



// Salvar os valores dos campos personalizados de produtos associados a esta categoria
add_action('edited_product_cat', 'save_category_products_order', 10, 2); // Prioridade 10

function save_category_products_order($term_id, $tt_id) {
    // Verificar se o nonce está definido e é válido
    if (!isset($_POST['category_products_order_nonce']) || !wp_verify_nonce($_POST['category_products_order_nonce'], 'save_category_products_order')) {
        return; // Sair da função se o nonce não for válido
    }

    // Verificar se o usuário tem permissão para editar a categoria
    if (!current_user_can('edit_term', $term_id)) {
        return; // Sair da função se o usuário não tem permissão
    }

    // Verificar e salvar os valores do campo de ordenação dos produtos
    if (isset($_POST['order_in_category']) && is_array($_POST['order_in_category'])) {
        foreach ($_POST['order_in_category'] as $product_id => $order_value) {
            // Sanitizar os valores
            $product_id = absint($product_id); // Assegurar que é um número inteiro
            $order_value = sanitize_text_field($order_value); // Limpar o valor do campo de texto
            // Atualizar o meta do post
            update_post_meta($product_id, 'order_in_category_' . $term_id, $order_value);
        }
    }

    // Sanitizar e salvar a opção de ordenação selecionada
    $product_ordering = isset($_POST['product_ordering']) ? sanitize_text_field($_POST['product_ordering']) : 'default';
    update_term_meta($term_id, 'product_ordering', $product_ordering);
}


