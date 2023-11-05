<?php
// Se desinstalação não for chamada pelo WordPress, sair
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Acessar a variável global do banco de dados
global $wpdb;

// Verifica se a opção de limpeza de dados está ativada
$cleanup_data = get_option('opbycat_cleanup_data');

if ($cleanup_data === 'yes') {
    // Remover metadados de post personalizados
    $meta_key_like = $wpdb->esc_like('order_in_category_') . '%';
    $wpdb->delete($wpdb->postmeta, ['meta_key' => $meta_key_like], ['%s']);

    // Remover metadados de termos personalizados
    $wpdb->delete($wpdb->termmeta, ['meta_key' => 'product_ordering'], ['%s']);

    // Remover opções do plugin
    delete_option('opbycat_enable_ordering');
    delete_option('opbycat_cleanup_data');
}

// Assegurar que o código acima seja executado apenas na desinstalação do plugin.
