# Resumo do Plugin de Ordenação de Produtos por Categoria para WooCommerce

## Visão Geral
- Nome do Plugin: Ordenação de Produtos por Categoria para WooCommerce
- Função: Permite a ordenação personalizada dos produtos dentro de categorias específicas no WooCommerce.
- Recursos Principais: Interface de arrastar e soltar para ordenação, independência da ordenação global, e ordenação específica por categoria.

## Funcionalidades
- **Ordenação Arrastável**: Administração fácil através de uma interface de arrastar e soltar na página de edição da categoria.
- **Ordenação Personalizada**: Capacidade de definir uma ordem específica para os produtos em cada categoria.
- **Integração com WooCommerce**: Funciona com as categorias padrão do WooCommerce sem conflitos.

## Uso
- **Instalação**: Tradicional via upload do arquivo ZIP no painel do WordPress e ativação pelo menu 'Plugins'.
- **Configuração**: Acessada através do caminho `WordPress Dashboard > Configurações > Ordenação por Categoria`, se aplicável.
- **Interface de Administração**: Na seção 'Produtos > Categorias', selecione uma categoria e use a interface de arrastar e soltar para ordenar os produtos.

## Hooks e Filtros
- **Ações**: 
  - `opbycat_product_ordering` - Dispara após a ordenação dos produtos ser salva.
  - `opbycat_settings_updated` - Dispara após as configurações do plugin serem atualizadas.
- **Filtros**: 
  - `opbycat_query_args` - Permite modificar os argumentos da consulta de produtos por categoria.

## Funções Principais
- `get_category_products( $category_id )`: Retorna produtos de uma categoria, ordenados de acordo com a ordenação personalizada.
- `save_category_products_order( $term_id )`: Salva a ordem dos produtos após a edição de uma categoria.
- `display_category_products_field( $term )`: Gera o campo no formulário de edição da categoria para a ordenação dos produtos.

## Estrutura de Dados
- **Post Meta**: 
  - `meta_key`: `order_in_category_{categoria_id}`.
  - `meta_value`: Valor numérico representando a posição do produto na ordenação.

## Documentação
- Disponível em `/docs`, com guias específicos para instalação, uso, guia para desenvolvedores, perguntas frequentes, changelog, e contribuição.

## Contribuição
- **Como Contribuir**: Instruções disponíveis em `docs/contributing.md`.
- **Relatório de Bugs**: Pelo rastreador de problemas no GitHub.
- **Sugestões de Funcionalidades**: Através de issues no GitHub.

## Changelog
- Mantido no arquivo `docs/changelog.md`, detalhando as mudanças a cada versão.

## Suporte
- Suporte oferecido através do rastreador de problemas no GitHub: `https://github.com/arielkeybob/order-products-by-category/issues`.

## Licença
- GPL v2 ou posterior.

---

Este resumo destina-se a fornecer uma compreensão rápida e abrangente do plugin de Ordenação de Produtos por Categoria para uso em interações com IA ou referência rápida para desenvolvedores e usuários.
