<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.0.5
Author: Ariel Souza
Author URI: https://arielsouza.com.br/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: order-products-by-category
*/

/* 
Este programa é um software livre; você pode redistribuí-lo e/ou 
modificá-lo sob os termos da Licença Pública Geral GNU, conforme 
publicada pela Free Software Foundation; tanto a versão 2 da 
Licença, ou (a seu critério) qualquer versão posterior.

Este programa é distribuído na esperança de que seja útil, mas 
SEM QUALQUER GARANTIA; sem mesmo a garantia implícita de 
COMERCIALIZAÇÃO ou ADEQUAÇÃO A UM DETERMINADO PROPÓSITO. Veja a 
Licença Pública Geral GNU para mais detalhes.

Você deve ter recebido uma cópia da Licença Pública Geral GNU 
junto com este programa.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { //phpcs:ignore
	add_action( 'admin_notices', 'opbc_admin_notice' );
}


define( 'PLUGIN_VERSION', '1.0.5' );

function order_products_by_category_load_textdomain() {
    load_plugin_textdomain('order-products-by-category', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('init', 'order_products_by_category_load_textdomain');


require_once(plugin_dir_path(__FILE__) . 'includes/plugin-links.php');
require_once(plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-fields.php');
require_once(plugin_dir_path(__FILE__) . 'includes/product-ordering.php');
require_once(plugin_dir_path(__FILE__) . 'includes/utility-functions.php');
require_once(plugin_dir_path(__FILE__) . 'includes/plugin-init.php');
require_once(plugin_dir_path(__FILE__) . 'includes/auto-adjustments.php');
require_once(plugin_dir_path(__FILE__) . 'includes/settings-page.php');


define('ORDER_PRODUCTS_BY_CATEGORY_URL', plugin_dir_url(__FILE__));

// Variável global para armazenar o valor de ordenação
global $order_value;

/**
 * Add admin notice if there is no woocommerce.
 *
 * @since    1.0.0
 */
function opbc_admin_notice() {
	?>
		<div class="notice notice-error">
			<p><?php esc_html_e( 'Product Rearrange for WooCommerce : Woocommerce should be activated before you can proceeed!', 'wcpr' ); ?></p>
		</div>
	<?php
}






