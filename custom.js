jQuery(function($) {
    // Torna a lista de itens arrastáveis
    $("#sortable-list").sortable({
        update: function(event, ui) {
            // Atualize os campos numéricos ocultos com a nova ordem
            $("#sortable-list li").each(function(index) {
                $(this).find(".hidden-field").val(index + 1);
            });
        }
    });

    // Define as propriedades da lista arrastável
    $("#sortable-list").disableSelection();
});
