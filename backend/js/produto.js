$(document).ready(function () {

    // 1. Pegar ID da URL
    const params = new URLSearchParams(window.location.search);
    const itemId = params.get('id');

    // Proteção: Se não tiver ID, avisa no console
    if (!itemId) {
        console.error("ID não encontrado na URL.");
        // Opcional: window.location.href = "index.html";
        return;
    }

    const urlItem = `https://fortnite-api.com/v2/cosmetics/br/${itemId}?language=en`;
    const urlShop = 'https://fortnite-api.com/v2/shop?language=en';

    // 2. Carregar Dados do Item
    $.getJSON(urlItem, function (response) {
        if (!response.data) return;
        const item = response.data;

        // Preenche Nome e Descrição
        $("#nome").text(item.name);
        $("#descricao").text(item.description || "Descrição não disponível para este item.");

        // Preenche TIPO (Esconde se vazio)
        if (item.type && item.type.displayValue) {
            $("#tipo").text(item.type.displayValue).show();
        } else {
            $("#tipo").hide();
        }

        // Preenche RARIDADE (Esconde se vazio)
        if (item.rarity && item.rarity.displayValue) {
            $("#raridade").text(item.rarity.displayValue).show();
        } else {
            $("#raridade").hide();
        }

        // Imagem
        let imgUrl = item.images.featured || item.images.icon || item.images.smallIcon || '../img/sem-imagem.png';
        $("#imagem").attr("src", imgUrl);

    }).fail(function() {
        $("#nome").text("Erro ao carregar item.");
    });

    // 3. Verificar Preço na Loja
    $.getJSON(urlShop, function (shop) {
        // Texto padrão
        $("#preco").text("Indisponível");
        
        if (shop.data && shop.data.entries) {
            let achou = false;

            shop.data.entries.forEach(entry => {
                if (entry.items) {
                    entry.items.forEach(shopItem => {
                        if (shopItem.id === itemId) {
                            // ACHOU NA LOJA HOJE
                            $("#preco").text(entry.finalPrice + " V-Bucks");
                            $(".btn-comprar").fadeIn(); // Mostra botão azul
                            achou = true;
                        }
                    });
                }
            });

            if (!achou) {
                $("#preco").text("Fora da Loja");
            }
        }
    }).fail(function() {
        $("#preco").text("Erro Shop");
    });

    // 4. Botão Voltar (Histórico)
    $(".btn-voltar").click(function() {
        window.history.back();
    });

});