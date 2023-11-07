<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.0.5
Author: Ariel Souza
*/

define( 'PLUGIN_VERSION', '1.0.5' );

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


