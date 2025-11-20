$(document).ready(function () {
    
    const $container = $("#novos"); 
    const urlNew = 'https://fortnite-api.com/v2/cosmetics/new?language=pt-BR';
    const urlShop = 'https://fortnite-api.com/v2/shop?language=pt-BR';

    let precosMap = new Map();

    // 1. Busca a Loja para pegar preços
    $.getJSON(urlShop, function(shopData) {
        if (shopData.data && shopData.data.entries) {
            shopData.data.entries.forEach(entry => {
                const price = entry.finalPrice;
                if (entry.items) entry.items.forEach(item => precosMap.set(item.id.toLowerCase(), price));
                if (entry.tracks) entry.tracks.forEach(t => precosMap.set(t.id.toLowerCase(), price));
                if (entry.cars) entry.cars.forEach(c => precosMap.set(c.id.toLowerCase(), price));
            });
        }
        carregarNovos();
    }).fail(function() {
        carregarNovos(); 
    });

    function carregarNovos() {
        $.getJSON(urlNew, function(newData) {
            $container.empty();

            let lista = newData.data && newData.data.items ? (newData.data.items.br || newData.data.items) : [];

            if (lista.length === 0) {
                $container.html('<p style="color:#333; text-align:center; width:100%;">Nenhum lançamento encontrado hoje.</p>');
                return;
            }

            // Limite de 10 itens
            lista = lista.slice(0, 8);

            lista.forEach(item => {
                const html = montarCardIdentidadeVisual(item);
                $container.append(html);
            });
        });
    }

    // FUNÇÃO ATUALIZADA: Estrutura igual ao Catálogo
    function montarCardIdentidadeVisual(item) {
        const id = item.id; // ID original para link
        const idLower = item.id.toLowerCase(); // ID para busca no mapa
        const nome = item.name || "Item Novo";
        const tipo = item.type ? item.type.displayValue : "Cosmético";
        
        // Raridade (Usada para classe CSS e Texto)
        const rClass = item.rarity ? item.rarity.value : "common";
        const rText = item.rarity ? item.rarity.displayValue : "Comum";
        
        const imgUrl = item.images.icon || item.images.smallIcon || 'img/sem-imagem.png';

        // Lógica de Preço e Botão
        let priceDisplay = "N/A";
        let btnClass = "btn-indisponivel";
        let btnText = "Indisponível";
        let inShop = false;

        if (precosMap.has(idLower)) {
            priceDisplay = precosMap.get(idLower);
            btnClass = "btn-comprar";
            btnText = "Comprar";
            inShop = true;
        }

        // Badges (Etiquetas)
        let badges = '<span class="badge badge-novo">NOVO</span>';
        if (inShop) {
            badges += '<span class="badge badge-venda">LOJA</span>';
        }

        // HTML IDÊNTICO AO DO CATÁLOGO
        return `
        <a href="paginas/produto.php?id=${id}" class="card-link">
            <div class="card">
                <div class="card-header">
                    <img src="${imgUrl}" class="card-img" alt="${nome}" loading="lazy" onerror="this.src='https://via.placeholder.com/200?text=Erro'">
                    <div class="badges-container">${badges}</div>
                </div>
                
                <div class="card-body">
                    <div class="card-info-top">
                        <span class="tipo">${tipo}</span>
                        <h5 title="${nome}">${nome}</h5>
                    </div>
                    
                    <hr>
                    
                    <div class="card-details">
                        <div class="valor">
                            <img src="https://fortnite-api.com/images/vbuck.png" class="vbuck-icon">
                            <span>${priceDisplay}</span>
                        </div>
                        <span class="raridade raridade-${rClass}">${rText}</span>
                    </div>

                    <button class="btn ${btnClass}">${btnText}</button>
                </div>
            </div>
        </a>
        `;
    }
});