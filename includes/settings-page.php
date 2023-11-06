<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Saída se acessado diretamente.
}

// Função de callback para sanitizar valores booleanos
function opbycat_callback( $input ) {
    // Se o valor de entrada for 'yes', retornar 'yes', caso contrário, retornar 'no'
    return $input === 'yes' ? 'yes' : 'no';
}

// Registrar as configurações do plugin
function opbycat_register_settings() {
    // Adicionar opções com valor padrão se ainda não estiverem definidas
    if ( false === get_option( 'opbycat_enable_ordering' ) ) {
        add_option( 'opbycat_enable_ordering', 'yes' );
    }
    if ( false === get_option( 'opbycat_cleanup_data' ) ) {
        add_option( 'opbycat_cleanup_data', 'no' );
    }
    
    // Registrar as opções com o callback de sanitização para valores booleanos
    register_setting( 'opbycat_options_group', 'opbycat_enable_ordering', 'opbycat_callback' );
    register_setting( 'opbycat_options_group', 'opbycat_cleanup_data', 'opbycat_callback' );
}
add_action( 'admin_init', 'opbycat_register_settings' );

// Registrar a página de opções
function opbycat_register_options_page() {
    add_options_page( 'Order by Category', 'Order by Category', 'manage_options', 'opbycat', 'opbycat_options_page' );
}
add_action( 'admin_menu', 'opbycat_register_options_page' );

// Renderizar a página de opções
function opbycat_options_page() {
    ?>
    <div class="wrap">
    <h2>Configurações de Ordenação por Categoria</h2>
    <form method="post" action="options.php">
    <?php
    settings_fields( 'opbycat_options_group' );
    do_settings_sections( 'opbycat_options_group' );
    ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Ativar Ordenação</th>
        <td><input type="checkbox" id="opbycat_enable_ordering" name="opbycat_enable_ordering" value="yes" <?php checked( 'yes', get_option( 'opbycat_enable_ordering' ), true ); ?>></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Deletar todos os dados na desinstalação</th>
        <td><input type="checkbox" id="opbycat_cleanup_data" name="opbycat_cleanup_data" value="yes" <?php checked( 'yes', get_option( 'opbycat_cleanup_data' ), true ); ?>></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>
    </form>
    </div>
    <?php
}
