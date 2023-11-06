# Guia para Desenvolvedores

Este guia detalha as funcionalidades internas do Plugin de Ordenação de Produtos por Categoria, fornecendo informações sobre APIs, hooks, filtros e como estender o plugin.

## Hooks e Filtros

O plugin utiliza vários hooks e filtros do WordPress para permitir que desenvolvedores estendam sua funcionalidade.

### Ações

- `opbycat_product_ordering`: Executado quando a ordem dos produtos é salva.
- `opbycat_settings_updated`: Disparado após as configurações do plugin serem atualizadas.

### Filtros

- `opbycat_query_args`: Permite modificar os argumentos da consulta que busca os produtos da categoria.

## API de Ordenação

Para interagir com a API de ordenação, você pode usar as seguintes funções:

```php
// Função para obter produtos ordenados por categoria
get_category_products( $category_id );

// Função para salvar a ordem dos produtos
save_category_products_order( $term_id );


Exemplos de Código
Aqui estão alguns exemplos de como você pode estender o plugin:
// Adicionando um filtro para modificar a consulta de produtos
add_filter( 'opbycat_query_args', 'customize_query_args' );
function customize_query_args( $args ) {
    // Modifique os argumentos da consulta
    return $args;
}
