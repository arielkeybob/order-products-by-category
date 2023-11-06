<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


function get_category_products($category_id, $per_page = 10, $page_number = 1) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $per_page,
        'paged' => $page_number,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'order_in_category_' . $category_id,
                'compare' => 'EXISTS',
            ),
            array(
                'key'     => 'order_in_category_' . $category_id,
                'compare' => 'NOT EXISTS',
                'value'   => '' // Placeholder para produtos sem valor definido.
            ),
        ),
    );

    // Permitir que os argumentos da consulta sejam modificados por outros desenvolvedores antes da consulta ser realizada
    $args = apply_filters('opbycat_get_category_products_args', $args, $category_id);

    // Busca os posts com os argumentos definidos
    $posts = get_posts($args);

    // Se nenhum produto for encontrado, retorne um array vazio ou um erro
    if (empty($posts)) {
        return apply_filters('opbycat_no_products_found', array(), $category_id);
    }

    // Ordenação manual dos posts com base no valor de meta personalizado
    usort($posts, function ($a, $b) use ($category_id) {
        $orderA = (int) get_post_meta($a->ID, 'order_in_category_' . $category_id, true);
        $orderB = (int) get_post_meta($b->ID, 'order_in_category_' . $category_id, true);

        return $orderA <=> $orderB; // Operador de comparação de nave espacial PHP 7
    });

    // Permitir que o resultado final seja modificado por outros desenvolvedores antes de ser retornado
    return apply_filters('opbycat_get_category_products', $posts, $category_id);
}


