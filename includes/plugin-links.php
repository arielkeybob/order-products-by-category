<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Saída se acessado diretamente.
}

if ( ! function_exists( 'my_plugin_settings_link' ) ) {
    // Adiciona o link de configurações ao plugin na lista de plugins.
    add_filter('plugin_action_links_' . plugin_basename(dirname(__DIR__) . '/order-products-by-category.php'), 'my_plugin_settings_link');

    function my_plugin_settings_link($links) {
        // Construa seu próprio link ou caminho.
        $settings_link = '<a href="' . admin_url('options-general.php?page=opbycat') . '">' . __('Settings') . '</a>';
        // Adicione o link de configurações ao array de links.
        array_unshift($links, $settings_link);
        return $links;
    }
}
