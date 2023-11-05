<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Add a custom field to the category edit page to display products
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20); // Priority 20

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
    echo '<label for="product_ordering">Order by:</label>';
    echo '<select id="product_ordering" name="product_ordering">';
    echo '<option value="title" ' . selected('title', $product_ordering, false) . '>Title</option>';
    echo '<option value="order_value" ' . selected('order_value', $product_ordering, false) . '>Custom Order</option>';
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

// Save the custom numeric field values of products associated with this category
add_action('edited_product_cat', 'save_category_products_order', 10, 2); // Priority 10

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
