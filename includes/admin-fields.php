<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Inclui o arquivo com funções úteis, uma vez que ele contém a função get_category_products
require_once plugin_dir_path( __FILE__ ) . 'utility-functions.php';

/**
 * Display the custom field for category products on the edit form.
 *
 * @param WP_Term $term The term object for the current taxonomy term.
 */
function display_category_products_field($term) {
    $ordering_enabled = get_ordering_status();
    if (!$ordering_enabled) {
        display_ordering_disabled_message();
        return;
    }

    wp_nonce_field('save_category_products_order', 'category_products_order_nonce');
    $category_id = $term->term_id;
    $product_ordering = get_term_meta($category_id, 'product_ordering', true);

    display_ordering_dropdown($product_ordering);
    display_products_list($category_id);
}

/**
 * Get the ordering status from options.
 *
 * @return bool True if ordering is enabled, false otherwise.
 */
function get_ordering_status() {
    return get_option('opbycat_enable_ordering') === 'yes';
}

/**
 * Display a message indicating that ordering is disabled.
 */
function display_ordering_disabled_message() {
    echo '<tr class="form-field"><th scope="row" valign="top"><label>' . esc_html__('A ordenação personalizada está desativada.', 'order-products-by-category') . '</label></th></tr>';
}

/**
 * Display the dropdown for selecting the ordering method.
 *
 * @param string $product_ordering The current ordering method.
 */
function display_ordering_dropdown($product_ordering) {
    echo '<tr class="form-field">';
    echo '<th scope="row" valign="top"><label for="product_ordering">' . esc_html__('Ordenar por:', 'order-products-by-category') . '</label></th>';
    echo '<td>';
    echo '<select id="product_ordering" name="product_ordering" class="postform">';
    echo '<option value="default" ' . selected('default', $product_ordering, !$product_ordering) . '>' . esc_html__('Padrão', 'order-products-by-category') . '</option>';
    echo '<option value="title" ' . selected('title', $product_ordering, false) . '>' . esc_html__('Título', 'order-products-by-category') . '</option>';
    echo '<option value="order_value" ' . selected('order_value', $product_ordering, false) . '>' . esc_html__('Ordem Personalizada', 'order-products-by-category') . '</option>';
    echo '</select>';
    echo '</td></tr>';
}


/**
 * Display the list of products for ordering.
 *
 * @param int $category_id The current category ID.
 */
function display_products_list($category_id) {
    $products = get_category_products($category_id);
    if (empty($products)) {
        echo '<tr class="form-field"><th scope="row" valign="top"></th><td>' . esc_html__('Nenhum produto nesta categoria.', 'order-products-by-category') . '</td></tr>';
        return;
    }

    echo '<tr class="form-field">';
    echo '<th scope="row" valign="top"></th>';
    echo '<td>';
    echo '<ul id="sortable-list">';
    foreach ($products as $product) {
        $product_id = $product->ID;
        $order_value = get_post_meta($product_id, 'order_in_category_' . $category_id, true);
        $order_value = $order_value !== '' ? $order_value : '0';
        echo '<li class="ui-state-default">';
        echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
        echo esc_html($product->post_title);
        echo '<input type="hidden" class="hidden-field" name="order_in_category[' . esc_attr($product_id) . ']" value="' . esc_attr($order_value) . '">';
        echo '</li>';
    }
    echo '</ul>';
    echo '</td></tr>';
}

/**
 * Save the ordering of products associated with the category.
 *
 * @param int $term_id Term ID of the category being saved.
 * @param int $tt_id   Term taxonomy ID.
 */
function save_category_products_order($term_id, $tt_id) {
    if (!is_valid_nonce('category_products_order_nonce', 'save_category_products_order')) {
        return;
    }

    if (!current_user_can_edit_term($term_id)) {
        return;
    }

    save_product_ordering($term_id);
}

/**
 * Check if the nonce is valid.
 *
 * @param string $nonce_name The name of the nonce to check.
 * @param string $action     The action associated with the nonce.
 *
 * @return bool True if the nonce is valid, false otherwise.
 */
function is_valid_nonce($nonce_name, $action) {
    return isset($_POST[$nonce_name]) && wp_verify_nonce($_POST[$nonce_name], $action);
}

/**
 * Check if the current user can edit the term.
 *
 * @param int $term_id The term ID.
 *
 * @return bool True if the current user can edit the term, false otherwise.
 */
function current_user_can_edit_term($term_id) {
    return current_user_can('edit_term', $term_id);
}

/**
 * Save product ordering.
 *
 * @param int $term_id The term ID of the category being saved.
 */
function save_product_ordering($term_id) {
    if (isset($_POST['order_in_category']) && is_array($_POST['order_in_category'])) {
        foreach ($_POST['order_in_category'] as $product_id => $order_value) {
            $product_id = absint($product_id);
            $order_value = sanitize_text_field($order_value);
            update_post_meta($product_id, 'order_in_category_' . $term_id, $order_value);
        }
    }

    $product_ordering = isset($_POST['product_ordering']) ? sanitize_text_field($_POST['product_ordering']) : 'default';
    update_term_meta($term_id, 'product_ordering', $product_ordering);
}

// Hook into WooCommerce to add and save custom fields.
add_action('product_cat_edit_form_fields', 'display_category_products_field', 20);
add_action('edited_product_cat', 'save_category_products_order', 10, 2);
