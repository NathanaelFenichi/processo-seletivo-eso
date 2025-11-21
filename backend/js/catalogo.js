$(document).ready(function () {
    
    // --- CONFIGURAÇÕES E VARIÁVEIS GLOBAIS ---
    let catalogoMap = new Map(); 
    let listaVisivel = [];       
    let paginaAtual = 1;
    const itensPorPagina = 25;
    
    // --- NOVO: Lista de itens que o usuário já tem ---
    let meusItens = []; 

    // Variáveis de Filtro
    let filtroTexto = "";
    let filtroTipo = "";
    let filtroRaridade = "";

    // Elementos do DOM
    const $container = $("#catalogo");
    const $statusPagina = $("#numero-pagina");

    // --- 1. CARREGAMENTO DE DADOS ---
    async function carregarDados() {
        $container.html('<div class="loading-msg">Carregando catálogo...</div>');

        try {
            // --- NOVO: [0] Busca itens que o usuário já comprou ---
            // Fazemos isso antes ou junto com a API para garantir que sabemos o que ele tem
            try {
                const resObtidos = await fetch('../backend/obtidos.php');
                const jsonObtidos = await resObtidos.json();
                if (jsonObtidos.sucesso) {
                    meusItens = jsonObtidos.ids; // Guarda os IDs num array global
                }
            } catch (err) {
                console.log("Usuário não logado ou erro ao buscar itens obtidos.");
            }

            // [A] Busca Base de Cosméticos (Todos os itens)
            const resCosmetics = await fetch('https://fortnite-api.com/v2/cosmetics/br?language=pt-BR');
            const jsonCosmetics = await resCosmetics.json();
            
            if (jsonCosmetics.data) {
                jsonCosmetics.data.forEach(item => {
                    catalogoMap.set(item.id.toLowerCase(), {
                        id: item.id,
                        name: item.name,
                        displayType: item.type?.displayValue || 'Item',
                        backendType: item.type?.value || 'misc', 
                        rarityValue: item.rarity?.value || 'common',
                        rarityDisplay: item.rarity?.displayValue || 'Comum',
                        image: item.images?.icon || item.images?.smallIcon,
                        isNew: false, 
                        inShop: false, 
                        price: null,
                        regularPrice: null, // NOVO: Pra promo
                        isPromo: false,     // NOVO: Flag de promoção
                        added: item.added ? new Date(item.added) : new Date() // NOVO: Data de inclusão
                    });
                });
            }

            // [B] Busca Itens "Novos"
            const resNew = await fetch('https://fortnite-api.com/v2/cosmetics/new?language=pt-BR');
            const jsonNew = await resNew.json();
            const newItems = jsonNew.data?.items?.br || jsonNew.data?.items || [];
            
            newItems.forEach(item => {
                const id = item.id.toLowerCase();
                if (catalogoMap.has(id)) {
                    catalogoMap.get(id).isNew = true;
                } else {
                    catalogoMap.set(id, {
                        id: item.id,
                        name: item.name,
                        displayType: item.type?.displayValue || 'Novo',
                        backendType: item.type?.value || 'misc',
                        rarityValue: item.rarity?.value || 'common',
                        rarityDisplay: item.rarity?.displayValue || 'Comum',
                        image: item.images?.icon || item.images?.smallIcon,
                        isNew: true, inShop: false, price: null,
                        regularPrice: null, // NOVO
                        isPromo: false,     // NOVO
                        added: item.added ? new Date(item.added) : new Date() // NOVO
                    });
                }
            });

            // [C] Busca Itens da Loja Diária
            const resShop = await fetch('https://fortnite-api.com/v2/shop?language=pt-BR');
            const jsonShop = await resShop.json();
            const entries = jsonShop.data?.entries || [];

            entries.forEach(entry => {
                let itensOferta = [];
                if(entry.items) itensOferta = itensOferta.concat(entry.items.map(i => ({...i, _source: 'item'})));
                if(entry.tracks) itensOferta = itensOferta.concat(entry.tracks.map(i => ({...i, _source: 'music'})));
                if(entry.cars) itensOferta = itensOferta.concat(entry.cars.map(i => ({...i, _source: 'car'})));

                if (itensOferta.length === 0 && entry.bundle) {
                    itensOferta.push({
                        id: `bundle_${entry.bundle.name.replace(/\s+/g, '')}`,
                        name: entry.bundle.name,
                        _source: 'bundle',
                        images: { icon: entry.bundle.image },
                    });
                }

                itensOferta.forEach(subItem => {
                    if(!subItem.id) return;
                    const id = subItem.id.toLowerCase();
                    const preco = entry.finalPrice;
                    const regularPreco = entry.regularPrice; // NOVO: Pra calcular promo
                    
                    let imgUrl = subItem.images?.icon || subItem.images?.smallIcon || subItem.albumArt;
                    if (entry.newDisplayAsset?.materialInstances?.[0]?.images?.Background) {
                        imgUrl = entry.newDisplayAsset.materialInstances[0].images.Background;
                    }

                    const nome = subItem.name || subItem.title || entry.bundle?.name || "Item Loja";
                    
                    let bType = subItem.type?.value || 'misc';
                    if(subItem._source === 'music') bType = 'music';
                    if(subItem._source === 'car') bType = 'car';

                    if (catalogoMap.has(id)) {
                        const item = catalogoMap.get(id);
                        item.inShop = true;
                        item.price = preco;
                        item.regularPrice = regularPreco; // NOVO
                        item.isPromo = (regularPreco > preco); // NOVO: Calcula se está em promo
                        if (!item.name) item.name = nome;
                        if (!item.image || item.image.includes('placeholder')) item.image = imgUrl;
                        if (bType === 'music') item.backendType = 'music';
                        if (!item.added && subItem.added) item.added = new Date(subItem.added); // NOVO: Garante data
                    } else {
                        catalogoMap.set(id, {
                            id: subItem.id,
                            name: nome,
                            displayType: subItem.type?.displayValue || (bType === 'music' ? 'Música' : 'Loja'),
                            backendType: bType,
                            rarityValue: subItem.rarity?.value || 'common',
                            rarityDisplay: subItem.rarity?.displayValue || 'Loja',
                            image: imgUrl || 'https://via.placeholder.com/200',
                            isNew: false, 
                            inShop: true, 
                            price: preco,
                            regularPrice: regularPreco, // NOVO
                            isPromo: (regularPreco > preco), // NOVO
                            added: subItem.added ? new Date(subItem.added) : new Date() // NOVO
                        });
                    }
                });
            });

            aplicarFiltros();

        } catch (e) {
            console.error(e);
            $container.html('<div class="loading-msg" style="color:red">Erro ao conectar com a API do Fortnite.</div>');
        }
    }

    // --- 2. LÓGICA DE FILTRAGEM (ATUALIZADA COM DATAS E PROMO) ---
    function aplicarFiltros() {
        const todos = Array.from(catalogoMap.values());
        
        const filtrarLoja = $("#filter-shop").is(":checked");
        const filtrarNovos = $("#filter-new").is(":checked");
        const filtrarPromo = $("#filter-promo").is(":checked"); // NOVO
        
        const dateFromVal = $("#date-from").val();
        const dateToVal = $("#date-to").val();
        const dateFrom = dateFromVal ? new Date(dateFromVal) : null;
        const dateTo = dateToVal ? new Date(dateToVal + 'T23:59:59') : null; // NOVO: Até o fim do dia

        listaVisivel = todos.filter(item => {
            if (filtroTexto && item.name && !item.name.toLowerCase().includes(filtroTexto)) return false;
            if (filtroTipo && (!item.backendType || !item.backendType.toLowerCase().includes(filtroTipo.toLowerCase()))) return false;
            if (filtroRaridade && (!item.rarityValue || item.rarityValue.toLowerCase() !== filtroRaridade.toLowerCase())) return false;
            if (filtrarLoja && !item.inShop) return false;
            if (filtrarNovos && !item.isNew) return false;
            if (filtrarPromo && !item.isPromo) return false; // NOVO

            // NOVO: Filtro de datas
            if (dateFrom && item.added < dateFrom) return false;
            if (dateTo && item.added > dateTo) return false;

            return true;
        });

        listaVisivel.sort((a, b) => {
            if (a.inShop !== b.inShop) return a.inShop ? -1 : 1;
            if (a.isNew !== b.isNew) return a.isNew ? -1 : 1;
            return 0;
        });

        paginaAtual = 1;
        renderizarPagina();
    }

    // --- 3. RENDERIZAÇÃO (HTML) ---
    function renderizarPagina() {
        $container.empty();
        
        if (listaVisivel.length === 0) {
            $container.html('<div class="loading-msg">Nenhum item encontrado.</div>');
            $statusPagina.text("0 / 0");
            return;
        }

        const totalPaginas = Math.ceil(listaVisivel.length / itensPorPagina);
        const inicio = (paginaAtual - 1) * itensPorPagina;
        const fim = inicio + itensPorPagina;
        const itensPagina = listaVisivel.slice(inicio, fim);

        let html = "";
        itensPagina.forEach(item => {
            html += montarCard(item);
        });

        $container.append(html);
        $statusPagina.text(`${paginaAtual} / ${totalPaginas}`);
        
        if (paginaAtual > 1) {
             $('html, body').animate({ scrollTop: $(".pesquisa-e-filtros").offset().top - 20 }, 'fast');
        }
    }

    function montarCard(item) {
    const nome = item.name || "Item sem nome";
    const tipo = item.displayType || "Cosmético";
    const rClass = item.rarityValue || "common"; 
    const rText = item.rarityDisplay || "Comum";
    const imgUrl = item.image || 'https://fortnite-api.com/images/cosmetics/br/bid_001_generic/icon.png';

    let badges = "";
    if (item.isNew) badges += '<span class="badge badge-novo">NOVO</span>';
    if (item.inShop) badges += '<span class="badge badge-venda">LOJA</span>';
    if (item.isPromo) badges += '<span class="badge badge-promo">PROMO</span>';

    // --- Lógica do Botão ADQUIRIDO ---
    let btnClass = "btn-indisponivel";
    let btnText = "Ver Detalhes";
    let priceDisplay = "Ver"; 
    let isDisabled = "";

    // 1. Verifica se o usuário JÁ TEM o item
    const jaTem = meusItens.includes(item.id.toLowerCase());

    if (jaTem) {
        btnClass = "btn-adquirido";
        btnText = "ADQUIRIDO";
        priceDisplay = "OK";
        isDisabled = "disabled";
    } 
    // 2. Se não tem, verifica se está na loja
    else if (item.inShop && item.price) {
        btnClass = "btn-comprar";
        btnText = "Comprar";
        if (item.isPromo) {  
            priceDisplay = `<span class="old-price">${item.regularPrice}</span> <span class="new-price">${item.price}</span>`;
        } else {
            priceDisplay = item.price; 
        }
    }

    const linkDestino = `produto.php?id=${item.id}`;

    // NOVO: Se for promo, coloca preço "em cima" (depois do nome)
    let promoPriceHTML = '';
    if (item.isPromo && !jaTem && item.inShop) {
        promoPriceHTML = `
            <div class="promo-price-top">
                <img src="../img/icons/vbuck.png" class="vbuck-icon">
                ${priceDisplay}
            </div>
        `;
    }

    // Preço normal fica em .valor se não for promo
    let normalPriceHTML = '';
    if (!item.isPromo && item.inShop && !jaTem) {
        normalPriceHTML = `
            <div class="valor">
                <img src="../img/icons/vbuck.png" class="vbuck-icon">
                <span>${priceDisplay}</span>
            </div>
        `;
    } else if (!item.inShop || jaTem) {
        normalPriceHTML = `
            <div class="valor">
                <span>${priceDisplay}</span>
            </div>
        `;
    }

    return `
    <a href="${linkDestino}" class="card-link">
        <div class="card">
            <div class="card-header">
                <img src="${imgUrl}" class="card-img" alt="${nome}" loading="lazy" onerror="this.src='https://via.placeholder.com/200?text=Erro'">
                <div class="badges-container">${badges}</div>
            </div>
            
            <div class="card-body">
                <div class="card-info-top">
                    <span class="tipo">${tipo}</span>
                    <h5 title="${nome}">${nome}</h5>
                    ${promoPriceHTML}  <!-- NOVO: Preço promo aqui em cima -->
                </div>
                <hr>
                <div class="card-details">
                    ${normalPriceHTML}  <!-- Preço normal aqui embaixo -->
                    <span class="raridade raridade-${rClass}">${rText}</span>
                </div>
                
                <button class="btn ${btnClass}" ${isDisabled}>${btnText}</button>
            </div>
        </div>
    </a>`;
}

    // --- 4. EVENTOS (ATUALIZADOS COM DATAS E PROMO) ---
    $(".dropdown-btn").click(function(e) {
        e.stopPropagation(); 
        $(".dropdown-menu").not($(this).next()).removeClass("show");
        $(this).next(".dropdown-menu").toggleClass("show");
    });

    $(document).click(function() {
        $(".dropdown-menu").removeClass("show");
    });

    $(".dropdown-menu a").click(function(e) {
        e.preventDefault();
        const val = $(this).data("value");
        const parentId = $(this).closest(".dropdown").attr("id");
        const btn = $(this).closest(".dropdown").find(".dropdown-btn");
        const text = $(this).text();

        if (parentId === "dropdown-tipo") {
            filtroTipo = val;
            btn.text(val ? `Tipo: ${text}` : "Tipo");
        } else {
            filtroRaridade = val;
            btn.text(val ? `Raridade: ${text}` : "Raridade");
        }
        aplicarFiltros();
    });

    $("#pesquisa").on("keyup", function() { 
        filtroTexto = $(this).val().toLowerCase(); 
        aplicarFiltros(); 
    });

    $("#filter-shop, #filter-new, #filter-promo, #date-from, #date-to").on("change", function() { // NOVO: Eventos pra promo e datas
        aplicarFiltros(); 
    });

    $("#prev").click(function() { 
        if (paginaAtual > 1) { 
            paginaAtual--; 
            renderizarPagina(); 
        } 
    });
    $("#next").click(function() { 
        const total = Math.ceil(listaVisivel.length / itensPorPagina); 
        if (paginaAtual < total) { 
            paginaAtual++; 
            renderizarPagina(); 
        } 
    });
    
    // Inicia tudo
    carregarDados();
});