<?php
/*
Plugin Name: Order Products to each category page
Description: Mostra a lista de produtos da categoria na página de edição da categoria com campos para modificar a ordem de exibição na página da categoria.
Version: 1.0.5
Author: Ariel Souza
*/

include_once('includes/enqueue-scripts.php');
include_once('includes/admin-fields.php');
include_once('includes/product-ordering.php');
include_once('includes/utility-functions.php');
include_once('includes/plugin-init.php');
include_once('includes/auto-adjustments.php');
include_once('includes/settings-page.php');


// Variável global para armazenar o valor de ordenação
global $order_value;















