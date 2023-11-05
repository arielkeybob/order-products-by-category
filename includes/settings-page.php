<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function opbycat_register_settings() {
    add_option('opbycat_enable_ordering', 'yes');
    register_setting('opbycat_options_group', 'opbycat_enable_ordering', 'opbycat_callback');
}
add_action('admin_init', 'opbycat_register_settings');

function opbycat_register_options_page() {
    add_options_page('Order by Category', 'Order by Category', 'manage_options', 'opbycat', 'opbycat_options_page');
}
add_action('admin_menu', 'opbycat_register_options_page');

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
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}
