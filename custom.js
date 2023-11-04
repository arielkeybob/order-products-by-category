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

    // Ocultar lista se "Por Título" for selecionado
    var productOrdering = $("#product_ordering").val();
    if (productOrdering === "title") {
        $("#sortable-list").hide();
    }

    // Monitorar a mudança na seleção
    $("#product_ordering").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === "title") {
            // Se "Por Título" for selecionado, oculte a lista
            $("#sortable-list").hide();
        } else {
            // Caso contrário, mostre a lista
            $("#sortable-list").show();
        }
    });
});
