$(document).ready(function () {

    // 1. Pega o ID da URL (ex: produto.php?id=CID_123)
    const params = new URLSearchParams(window.location.search);
    const itemId = decodeURIComponent(params.get('id'));

    // Se não tiver ID, mostra erro na tela
    if (!itemId) { 
        $("#nome").text("Item não especificado."); 
        $(".btn-comprar").hide();
        return; 
    }

    // URLs da API
    const urlItem = `https://fortnite-api.com/v2/cosmetics/br/${itemId}?language=pt-BR`;
    const urlShop = 'https://fortnite-api.com/v2/shop?language=pt-BR';

    let precoItem = 0;
    let jaPossui = false; 

    // --- 2. VERIFICA SE JÁ TEM O ITEM NO BANCO ---
    $.ajax({
        url: '../backend/verificar_posse.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: itemId }),
        success: function(res) {
            if (res.tem_item) {
                jaPossui = true;
                atualizarBotao(); // Muda botão para verde "Adquirido"
            }
        },
        error: function() {
            console.log("Não foi possível verificar a posse do item (usuário deslogado ou erro).");
        }
    });

    // --- 3. CARREGA DADOS VISUAIS DO ITEM (FOTO, NOME) ---
    $.getJSON(urlItem, function (res) {
        if (res.data) renderizarTela(res.data);
    }).fail(function() {
        console.log("Item não encontrado na API de cosméticos, tentando dados da loja...");
    });

    // --- 4. VERIFICA DISPONIBILIDADE E PREÇO NA LOJA ---
    $.getJSON(urlShop, function (shop) {
        let estaNaLoja = false;

        if (shop.data && shop.data.entries) {
            shop.data.entries.forEach(entry => {
                // Cria lista de todos os itens dessa oferta
                let itens = [].concat(entry.items || [], entry.tracks || [], entry.cars || []);
                
                // Procura nosso item nessa lista
                const achou = itens.find(i => i.id.toLowerCase() === itemId.toLowerCase());
                
                // Verifica também se é um Pacote/Bundle
                let isBundle = false;
                if (entry.bundle && entry.bundle.name) {
                    const bundleId = `bundle_${entry.bundle.name.replace(/\s+/g, '')}`;
                    if (decodeURIComponent(bundleId) === itemId) isBundle = true;
                }

                // Se encontrou o item nesta oferta da loja
                if (achou || isBundle) {
                    estaNaLoja = true;
                    precoItem = entry.finalPrice;
                    
                    // Fallback: Se a busca principal falhou (passo 3), preenche com dados da loja
                    if ($("#nome").text().includes("Carregando") || $("#nome").text().includes("Item não especificado")) {
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

        // Atualiza o visual do preço
        const $preco = $("#preco");
        if (estaNaLoja) {
            $preco.html(`${precoItem} <img src="../img/icons/vbuck.png" width="24" style="vertical-align:bottom">`);
            $preco.css("color", "#33CC99");
            atualizarBotao(); 
        } else {
            $preco.text("Indisponível");
            $preco.css("color", "#aaa");
            // Se não está na loja e não tem o item, esconde o botão de comprar
            if (!jaPossui) $(".btn-comprar").hide();
        }
    });

    // Função para preencher o HTML
    function renderizarTela(item) {
        $("#nome").text(item.name || item.title || "Item Desconhecido");
        $("#descricao").text(item.description || "Sem descrição disponível.");
        $("#tipo").text(item.type ? item.type.displayValue : "Cosmético");
        
        const img = item.images?.featured || item.images?.icon || item.albumArt || '../img/sem-imagem.png';
        $("#imagem").attr("src", img);

        if (item.rarity) {
            $("#raridade").text(item.rarity.displayValue)
                .removeClass() // Remove classes anteriores
                .addClass("badge-raridade")
                .addClass(`rarity-${item.rarity.value ? item.rarity.value.toLowerCase() : 'common'}`);
        }
        document.title = `${$("#nome").text()} - Loja Fortnite`;
    }

    // Função para controlar estado do botão
    function atualizarBotao() {
        const $btn = $(".btn-comprar");
        if (jaPossui) {
            $btn.text("ADQUIRIDO ✓")
                .css("background-color", "#27ae60") // Verde
                .prop("disabled", true)
                .show();
        } else {
            $btn.text("Comprar")
                .css("background-color", "#f3af19") // Amarelo/Azul padrão
                .prop("disabled", false)
                .show();
        }
    }

    // --- 5. AÇÃO DE CLICAR EM COMPRAR ---
    $(".btn-comprar").click(function() {
        if (jaPossui) return;

        if (!confirm(`Deseja comprar este item por ${precoItem} V-Bucks?`)) return;

        const $btn = $(this);
        const textoOriginal = $btn.text();
        $btn.text("Processando...").prop("disabled", true);

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
                    
                    // Atualiza o saldo na navbar instantaneamente
                    if(res.novo_saldo !== undefined) {
                        $(".qtd-V-bucks h2").text(res.novo_saldo.toLocaleString('pt-BR'));
                    }
                } else {
                    alert("❌ " + res.msg);
                    $btn.text(textoOriginal).prop("disabled", false);
                }
            },
            // Tratamento de erros melhorado para debugar
            error: function(xhr, status, error) {
                console.log("Erro bruto:", xhr.responseText);
                
                if (xhr.status === 404) {
                    alert("ERRO 404: O arquivo 'backend/comprar.php' não foi encontrado! Verifique a pasta.");
                } else if (xhr.status === 500) {
                    alert("ERRO 500: Erro interno no servidor PHP. Verifique o código do comprar.php.");
                } else {
                    alert("Erro de conexão: " + xhr.status + " - " + error);
                }
                $btn.text(textoOriginal).prop("disabled", false);
            }
        });
    });

    // Botão Voltar
    $(".btn-voltar").click(function() {
        window.history.back();
    });
});