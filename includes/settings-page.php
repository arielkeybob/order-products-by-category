<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Registrar as configurações do plugin
function opbycat_register_settings() {
    // Registrar a opção e adicionar uma opção de limpeza de dados
    add_option('opbycat_enable_ordering', 'yes');
    add_option('opbycat_cleanup_data', 'no'); // Adicionar com o valor padrão 'no'
    
    // Registrar as opções com um callback de sanitização
    register_setting('opbycat_options_group', 'opbycat_enable_ordering', 'opbycat_callback');
    register_setting('opbycat_options_group', 'opbycat_cleanup_data', 'opbycat_callback'); // Registrar a nova opção
}
add_action('admin_init', 'opbycat_register_settings');

// Registrar a página de opções
function opbycat_register_options_page() {
    add_options_page('Order by Category', 'Order by Category', 'manage_options', 'opbycat', 'opbycat_options_page');
}
add_action('admin_menu', 'opbycat_register_options_page');

// Renderizar a página de opções
function opbycat_options_page() {
    ?>
    <div>
    <?php screen_icon(); ?>
    <h2>Configurações de Ordenação por Categoria</h2>
    <form method="post" action="options.php">
    <?php settings_fields('opbycat_options_group'); ?>
    <h3>Configurações Gerais</h3>
    <p>Ativar ou desativar a ordenação para todas as categorias:</p>
    <input type="checkbox" id="opbycat_enable_ordering" name="opbycat_enable_ordering" value="yes" <?php checked('yes', get_option('opbycat_enable_ordering'), true); ?>>
    <label for="opbycat_enable_ordering">Ativar Ordenação</label>
    
    <h3>Opções de Desinstalação</h3>
    <p>Escolha se os dados devem ser deletados ao desinstalar o plugin:</p>
    <input type="checkbox" id="opbycat_cleanup_data" name="opbycat_cleanup_data" value="yes" <?php checked('yes', get_option('opbycat_cleanup_data'), true); ?>>
    <label for="opbycat_cleanup_data">Deletar todos os dados na desinstalação</label>
    
    <?php submit_button(); ?>
    </form>
    </div>
    <?php
}
