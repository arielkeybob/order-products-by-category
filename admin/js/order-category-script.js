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

    // Ocultar lista se "Por Título", "Padrão" for selecionado ou se não houver valor definido
    var productOrdering = $("#product_ordering").val() || "default"; // Define "default" como fallback
    if (productOrdering === "title" || productOrdering === "default") {
        $("#sortable-list").hide();
    }

    // Monitorar a mudança na seleção
    $("#product_ordering").change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === "title" || selectedValue === "default") {
            // Se "Por Título" ou "Padrão" for selecionado, oculte a lista
            $("#sortable-list").hide();
        } else {
            // Caso contrário, mostre a lista
            $("#sortable-list").show();
        }
    });
});
