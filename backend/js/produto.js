$(document).ready(function () {

    // 1. Pegar ID da URL
    const params = new URLSearchParams(window.location.search);
    // Decodifica caso venha com caracteres especiais (acento em Pacotão)
    const itemId = decodeURIComponent(params.get('id'));
    
    let itemCarregado = false;

    if (!itemId) {
        $("#nome").text("Item não especificado.");
        return;
    }

    // --- FUNÇÃO PARA ATUALIZAR A TELA ---
    function renderizarItem(dados) {
        // Se já carregamos da loja (fonte mais rica para preços), não sobrescreve com dados básicos
        if (itemCarregado && dados.fonte === "loja" && $("#nome").text() !== "Buscando na loja...") return;
        
        itemCarregado = true;

        const nome = dados.name || dados.title || "Nome Desconhecido";
        const descricao = dados.description || "Sem descrição disponível.";
        const tipo = dados.type ? dados.type.displayValue : "Cosmético";
        
        let raridadeNome = "Comum";
        let raridadeClasse = "common";
        if (dados.rarity) {
            raridadeNome = dados.rarity.displayValue;
            raridadeClasse = dados.rarity.value ? dados.rarity.value.toLowerCase() : "common";
        }

        // Imagem (Cascata de tentativas)
        const imagem = dados.images?.featured || 
                       dados.images?.icon || 
                       dados.images?.smallIcon || 
                       dados.albumArt || 
                       dados.cover || 
                       '../img/sem-imagem.png';

        // Aplica no HTML
        $("#nome").text(nome);
        $("#descricao").text(descricao);
        $("#tipo").text(tipo);
        
        const $badgeRaridade = $("#raridade");
        $badgeRaridade.text(raridadeNome);
        $badgeRaridade.removeClass().addClass(`badge-raridade rarity-${raridadeClasse}`);
        $badgeRaridade.show();

        $("#imagem").attr("src", imagem);
        document.title = `${nome} - Detalhes`;
    }

    // --- 2. TENTATIVA 1: BUSCA NORMAL (Apenas se NÃO for ID de Bundle artificial) ---
    if (!itemId.startsWith('bundle_')) {
        $.getJSON(`https://fortnite-api.com/v2/cosmetics/br/${itemId}?language=pt-BR`, function(response) {
            if (response.data) {
                renderizarItem({...response.data, fonte: "br"});
            }
        }).fail(function() {
             // Fallbacks (Carros, Músicas...)
             // ... (mantive simplificado aqui, a lógica completa está na versão anterior se precisar)
             // Se tudo falhar, o passo 3 resolve.
             if (!itemCarregado) $("#nome").text("Buscando na loja...");
        });
    } else {
        // Se for bundle, já pula direto pra mensagem de busca
        $("#nome").text("Buscando Pacote na Loja...");
    }

    // --- 3. VERIFICAÇÃO DE PREÇO E DISPONIBILIDADE (LOJA) ---
    $.getJSON('https://fortnite-api.com/v2/shop?language=pt-BR', function (shop) {
        let estaNaLoja = false;
        let precoEncontrado = 0;
        let dadosDaLoja = null;

        if (shop.data && shop.data.entries) {
            shop.data.entries.forEach(entry => {
                
                // A. Lógica para Itens Normais (Pelo ID)
                let achouItemNormal = false;
                if (!itemId.startsWith('bundle_')) {
                    let itensDaOferta = [];
                    if (entry.items) itensDaOferta = itensDaOferta.concat(entry.items);
                    if (entry.tracks) itensDaOferta = itensDaOferta.concat(entry.tracks);
                    if (entry.cars) itensDaOferta = itensDaOferta.concat(entry.cars);
                    if (entry.instruments) itensDaOferta = itensDaOferta.concat(entry.instruments);

                    const itemReal = itensDaOferta.find(i => i.id.toLowerCase() === itemId.toLowerCase());
                    if (itemReal) {
                        achouItemNormal = true;
                        dadosDaLoja = itemReal;
                    }
                }

                // B. Lógica para Bundles Artificiais (Pelo Nome gerado)
                let achouBundle = false;
                if (entry.bundle && entry.bundle.name) {
                    // Recria o ID artificial para comparar
                    const idGerado = `bundle_${entry.bundle.name.replace(/\s+/g, '')}`;
                    // Decodifica ambos para garantir que acentos batam (Pacotão vs Pacot%C3%A3o)
                    if (decodeURIComponent(idGerado) === decodeURIComponent(itemId)) {
                        achouBundle = true;
                        // Cria um objeto de dados "fake" baseado no bundle para preencher a tela
                        dadosDaLoja = {
                            id: itemId,
                            name: entry.bundle.name,
                            description: entry.bundle.info || "Pacote disponível na Loja de Itens.",
                            type: { displayValue: "Pacote" },
                            rarity: { value: "legendary", displayValue: "Loja" },
                            images: { featured: entry.bundle.image, icon: entry.bundle.image }
                        };
                    }
                }

                // Se achou de qualquer jeito
                if (achouItemNormal || achouBundle) {
                    estaNaLoja = true;
                    precoEncontrado = entry.finalPrice;
                    
                    // Se achou imagem melhor na oferta (fundo customizado)
                    if (entry.newDisplayAsset?.materialInstances?.[0]?.images?.Background) {
                        dadosDaLoja.shopImage = entry.newDisplayAsset.materialInstances[0].images.Background;
                    }
                }
            });
        }

        // ATUALIZAÇÃO DE PREÇO (Botão)
        const $precoBox = $("#preco");
        const $btnComprar = $(".btn-comprar");

        if (estaNaLoja) {
            $precoBox.html(`${precoEncontrado} <img src="https://fortnite-api.com/images/vbuck.png" style="width:24px; vertical-align:bottom;">`);
            $precoBox.css("color", "#33CC99");
            $btnComprar.fadeIn().css("display", "flex");
            
            // Se a API principal não achou (ou era bundle), usamos os dados da loja
            if (dadosDaLoja) {
                // Prioriza imagem da loja
                if (dadosDaLoja.shopImage) dadosDaLoja.images = { featured: dadosDaLoja.shopImage };
                
                renderizarItem({...dadosDaLoja, fonte: "loja"});
            }
        } else {
            $precoBox.text("Indisponível");
            $precoBox.css("color", "#aaa");
            $btnComprar.hide();
            
            if (!itemCarregado) {
                $("#nome").text("Item não encontrado.");
                $("#descricao").text("Este item saiu da loja ou o link está incorreto.");
                $("#imagem").hide();
            }
        }

    }).fail(function() {
        $("#preco").text("Erro Loja");
    });

    // 4. Botão Voltar
    $(".btn-voltar").click(function() {
        if (document.referrer) {
            window.history.back();
        } else {
            window.location.href = '../index.php';
        }
    });
});