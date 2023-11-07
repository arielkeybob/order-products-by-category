<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.0.5
Author: Ariel Souza
Author URI: https://arielsouza.com.br/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 
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


