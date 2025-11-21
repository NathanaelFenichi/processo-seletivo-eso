$(document).ready(function () {

    const params = new URLSearchParams(window.location.search);
    const itemId = decodeURIComponent(params.get('id'));

    if (!itemId) { $("#nome").text("Item não especificado."); return; }

    const urlItem = `https://fortnite-api.com/v2/cosmetics/br/${itemId}?language=pt-BR`;
    const urlShop = 'https://fortnite-api.com/v2/shop?language=pt-BR';

    let precoItem = 0;
    let jaPossui = false; // Estado local

    // --- 1. VERIFICA SE JÁ TEM O ITEM (Chama PHP) ---
    $.ajax({
        url: '../backend/verificar_posse.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: itemId }),
        success: function(res) {
            if (res.tem_item) {
                jaPossui = true;
                atualizarBotao(); // Já muda o botão para verde
            }
        }
    });

    // --- 2. CARREGA DADOS VISUAIS ---
    $.getJSON(urlItem, function (res) {
        if (res.data) renderizarTela(res.data);
    }).fail(function() {
        console.log("Buscando detalhes na loja...");
    });

    // --- 3. VERIFICA PREÇO NA LOJA ---
    $.getJSON(urlShop, function (shop) {
        let estaNaLoja = false;

        if (shop.data && shop.data.entries) {
            shop.data.entries.forEach(entry => {
                let itens = [].concat(entry.items || [], entry.tracks || [], entry.cars || []);
                const achou = itens.find(i => i.id.toLowerCase() === itemId.toLowerCase());
                
                // Verifica Bundle/Pacote
                let isBundle = false;
                if (entry.bundle && entry.bundle.name) {
                    const bundleId = `bundle_${entry.bundle.name.replace(/\s+/g, '')}`;
                    if (decodeURIComponent(bundleId) === itemId) isBundle = true;
                }

                if (achou || isBundle) {
                    estaNaLoja = true;
                    precoItem = entry.finalPrice;
                    
                    // Se API principal falhou, usa dados da loja
                    if ($("#nome").text().includes("Carregando")) {
                        const dadosLoja = achou || {
                            name: entry.bundle.name,
                            description: "Pacote da Loja",
                            type: { displayValue: 'Pacote' },
                            images: { featured: entry.bundle.image }
                        };
                        renderizarTela(dadosLoja);
                    }
                }
            });
        }

        const $preco = $("#preco");
        if (estaNaLoja) {
            $preco.html(`${precoItem} <img src="../img/icons/vbuck.png" width="24" style="vertical-align:bottom">`);
            $preco.css("color", "#33CC99");
            atualizarBotao(); // Garante estado correto
        } else {
            $preco.text("Indisponível");
            $preco.css("color", "#aaa");
            if (!jaPossui) $(".btn-comprar").hide();
        }
    });

    function renderizarTela(item) {
        $("#nome").text(item.name || item.title || "Item");
        $("#descricao").text(item.description || "Sem descrição.");
        $("#tipo").text(item.type ? item.type.displayValue : "Cosmético");
        
        const img = item.images?.featured || item.images?.icon || item.albumArt || '../img/sem-imagem.png';
        $("#imagem").attr("src", img);

        if (item.rarity) {
            $("#raridade").text(item.rarity.displayValue)
                .addClass(`rarity-${item.rarity.value ? item.rarity.value.toLowerCase() : 'common'}`);
        }
        document.title = `${item.name} - Detalhes`;
    }

    function atualizarBotao() {
        const $btn = $(".btn-comprar");
        if (jaPossui) {
            $btn.text("ADQUIRIDO ✓")
                .css("background-color", "#27ae60")
                .prop("disabled", true)
                .show();
        } else {
            $btn.text("Adicionar ao Carrinho")
                .css("background-color", "#1da1f2")
                .prop("disabled", false)
                .show();
        }
    }

    // --- 4. AÇÃO DE COMPRAR ---
    $(".btn-comprar").click(function() {
        if (jaPossui) return;

        if (!confirm(`Comprar por ${precoItem} V-Bucks?`)) return;

        const $btn = $(this);
        $btn.text("Processando...");

        $.ajax({
            url: '../backend/comprar.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                id: itemId,
                preco: precoItem
            }),
            success: function(res) {
                if (res.sucesso) {
                    alert("🎉 " + res.msg);
                    jaPossui = true;
                    atualizarBotao();
                    // Atualiza saldo visualmente
                    if(res.novo_saldo !== undefined) $(".qtd-V-bucks h2").text(res.novo_saldo);
                } else {
                    alert("❌ " + res.msg);
                    atualizarBotao(); // Reseta texto
                }
            },
            error: function() {
                alert("Erro de conexão.");
                atualizarBotao();
            }
        });
    });

    $(".btn-voltar").click(function() {
        window.history.back();
    });
});