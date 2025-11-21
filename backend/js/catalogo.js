$(document).ready(function() {
    carregarCatalogo();

    $('#pesquisa').on('input', function() {
        filtrarItens();
    });

    $('#dropdown-tipo .dropdown-menu a').on('click', function(e) {
        e.preventDefault();
        const valor = $(this).data('value');
        $('#dropdown-tipo .dropdown-btn').text($(this).text());
        $('#dropdown-tipo .dropdown-btn').data('selected', valor);
        filtrarItens();
    });

    $('#dropdown-raridade .dropdown-menu a').on('click', function(e) {
        e.preventDefault();
        const valor = $(this).data('value');
        $('#dropdown-raridade .dropdown-btn').text($(this).text());
        $('#dropdown-raridade .dropdown-btn').data('selected', valor);
        filtrarItens();
    });

    $('#filter-shop, #filter-new').on('change', function() {
        filtrarItens();
    });

    $('#prev').on('click', function() {
        if (paginaAtual > 1) {
            paginaAtual--;
            renderizarCatalogo();
        }
    });

    $('#next').on('click', function() {
        if (paginaAtual < totalPaginas) {
            paginaAtual++;
            renderizarCatalogo();
        }
    });

    $('.dropdown-btn').on('click', function() {
        $(this).next('.dropdown-menu').toggle();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').hide();
        }
    });
});
