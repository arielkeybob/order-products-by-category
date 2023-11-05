<?php
// Se uninstall.php não for chamado pelo WordPress, saia
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Limpeza de dados: remove opções, tabelas e metadados

// Opção para verificar se os dados devem ser removidos
$should_cleanup = get_option('opbycat_cleanup_data_on_uninstall');

// Remove metadados de produtos e opções apenas se o usuário optou por isso
if ($should_cleanup === 'yes') {
    global $wpdb;

    // Remove opções
    delete_option('opbycat_enable_ordering');
    delete_option('opbycat_cleanup_data_on_uninstall');

    // Remove metadados dos produtos
    // Substitua 'order_in_category_' pelo prefixo correto que você usou em seus metadados
    $meta_key_like = 'order_in_category_%';
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $wpdb->postmeta WHERE meta_key LIKE %s",
            $meta_key_like
        )
    );

    // Remove metadados das categorias
    // Substitua 'product_ordering' pelo ID correto que você usou em seus metadados de termo
    $term_meta_key = 'product_ordering';
    $wpdb->delete(
        $wpdb->termmeta,
        ['meta_key' => $term_meta_key],
        ['%s']
    );
}

// Para segurança extra, verifique se o usuário tem permissão para deletar plugins antes de executar qualquer coisa
if (!current_user_can('delete_plugins')) {
    exit();
}
