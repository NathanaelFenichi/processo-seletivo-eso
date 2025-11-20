$(document).ready(function () {

    // =========================
    // CONFIGURAÇÕES E VARIÁVEIS GLOBAIS
    // =========================
    // 1. Links Corrigidos (Battle Royale + Inglês)
    const urlShop = 'https://fortnite-api.com/v2/shop?language=en';
    const urlCosmeticos = 'https://fortnite-api.com/v2/cosmetics/br?language=en';
    const urlNew = 'https://fortnite-api.com/v2/cosmetics/br/new?language=en';

    let venda = [];       // IDs dos itens na loja hoje
    let todos = [];       // Lista completa de cosméticos (milhares)
    let novos = [];       // IDs dos itens novos
    let precos = {};      // Mapa de Preços: ID -> Preço

    let listaFiltrada = []; // Lista temporária usada na busca/filtros

    let carregado = 0;    // Contador de requisições

    // Paginação
    let paginaAtual = 1;
    const itensPorPagina = 24; // Quantos cards aparecem por vez

    // Filtros Atuais
    let filtroTexto = "";
    let filtroTipo = "";
    let filtroRaridade = "";

    // =========================
    // REQUISIÇÕES AJAX
    // =========================

    // 1) SHOP
    $.getJSON(urlShop, function (shop) {
        if (shop.data && shop.data.entries) {
            shop.data.entries.forEach(entry => {
                // Alguns entries têm múltiplos itens (bundles)
                if (entry.items) {
                    entry.items.forEach(item => {
                        precos[item.id] = entry.finalPrice;
                        venda.push(item.id);
                    });
                }
            });
        }
        tentarMontar();
    }).fail(function() { console.error("Erro ao carregar Shop"); tentarMontar(); });

    // 2) COSMÉTICOS (A lista gigante)
    $.getJSON(urlCosmeticos, function (cosmeticos) {
        // Atenção: No link /br, a lista está direto em 'data', não em 'data.items'
        let lista = cosmeticos.data; 
        if (Array.isArray(lista)) {
            todos = lista;
        } else {
            console.error("Formato de cosméticos inesperado");
        }
        tentarMontar();
    }).fail(function() { console.error("Erro ao carregar Cosméticos"); tentarMontar(); });

    // 3) NOVOS
    $.getJSON(urlNew, function (novosData) {
        // No link /br/new, a lista pode estar em data.items ou data.build
        // Vamos tentar pegar de forma segura
        let lista = novosData.data && novosData.data.items ? novosData.data.items : [];
        
        if (Array.isArray(lista)) {
            novos = lista.map(i => i.id);
        }
        tentarMontar();
    }).fail(function() { console.error("Erro ao carregar Novos"); tentarMontar(); });

    // Verifica se tudo carregou
    function tentarMontar() {
        carregado++;
        if (carregado === 3) {
            console.log("Tudo carregado! Total itens:", todos.length);
            aplicarFiltros(); // Inicia a primeira renderização
        }
    }

    // =========================
    // LÓGICA DE FILTROS E PAGINAÇÃO
    // =========================

    // Função principal que decide O QUE mostrar
    function aplicarFiltros() {
        // 1. Começa com todos
        listaFiltrada = todos;

        // 2. Filtra por Texto (Pesquisa)
        if (filtroTexto) {
            listaFiltrada = listaFiltrada.filter(item => 
                item.name.toLowerCase().includes(filtroTexto)
            );
        }

        // 3. Filtra por Tipo (Outfit, Pickaxe, etc)
        if (filtroTipo) {
            listaFiltrada = listaFiltrada.filter(item => 
                item.type && item.type.value.toLowerCase() === filtroTipo
            );
        }

        // 4. Filtra por Raridade
        if (filtroRaridade) {
            listaFiltrada = listaFiltrada.filter(item => 
                item.rarity && item.rarity.value.toLowerCase() === filtroRaridade
            );
        }

        // Reseta para página 1 sempre que filtrar
        paginaAtual = 1;
        renderizarPagina();
    }

    // Função que desenha a página atual na tela
    function renderizarPagina() {
        const container = $("#catalogo");
        container.empty(); // Limpa o anterior

        if (listaFiltrada.length === 0) {
            container.html("<p style='color:white; text-align:center; width:100%;'>Nenhum item encontrado.</p>");
            $("#numero-pagina").text(0);
            return;
        }

        // Cálculos da paginação
        const inicio = (paginaAtual - 1) * itensPorPagina;
        const fim = inicio + itensPorPagina;
        const itensDaPagina = listaFiltrada.slice(inicio, fim);

        let html = "";

        itensDaPagina.forEach(item => {
            let preco = precos[item.id] || "N/A";
            let ehNovo = novos.includes(item.id);
            let estaAVenda = venda.includes(item.id);
            
            // Verifica imagem (as vezes icon é null, usa smallIcon)
            let imgUrl = item.images.icon || item.images.smallIcon || '../img/sem-imagem.png';

            html += montarCard(item, preco, ehNovo, estaAVenda, imgUrl);
        });

        container.append(html);
        
        // Atualiza número da página
        $("#numero-pagina").text(paginaAtual);
    }

    // Montar HTML do Card
    function montarCard(item, preco, ehNovo, estaAVenda, imgUrl) {
        let vendaBadge = estaAVenda ? '<img src="../img/icons/icon-venda.png" class="badge-icon" title="Na Loja!">' : '';
        let novoBadge = ehNovo ? '<img src="../img/icons/icon-novo.png" class="badge-icon" title="Novo!">' : '';
        
        // Corrigindo possíveis erros de leitura de propriedade
        let tipo = item.type ? item.type.displayValue : "Item";
        let raridade = item.rarity ? item.rarity.displayValue : "Comum";

        return `
          <a href="produto.php?id=${item.id}" class="card-link">
            <div class="card">
              <img src="${imgUrl}" class="card-img" alt="${item.name}">
              <div class="card-body">
                <div class="card-div">
                  <div class="titulo">
                    <p class="tipo">${tipo}</p>
                    <h5 class="card-name">${item.name} ${novoBadge} ${vendaBadge}</h5>
                  </div>
                </div>
                <hr>
                <div class="card-div">
                  <p class="valor">V-bucks: <span>${preco}</span></p>
                  <p class="raridade">${raridade}</p>
                </div>
                <button class="btn-comprar">Ver Detalhes</button>
              </div>
            </div>
          </a>
        `;
    }

    // =========================
    // EVENTOS (CLIQUES E DIGITAÇÃO)
    // =========================

    // Botão Anterior
    $("#prev").click(function() {
        if (paginaAtual > 1) {
            paginaAtual--;
            renderizarPagina();
            $('html, body').animate({ scrollTop: 0 }, 'fast'); // Sobe a tela
        }
    });

    // Botão Próximo
    $("#next").click(function() {
        const totalPaginas = Math.ceil(listaFiltrada.length / itensPorPagina);
        if (paginaAtual < totalPaginas) {
            paginaAtual++;
            renderizarPagina();
            $('html, body').animate({ scrollTop: 0 }, 'fast'); // Sobe a tela
        }
    });

    // Campo de Pesquisa
    $("#pesquisa").on("input", function() {
        filtroTexto = $(this).val().toLowerCase();
        aplicarFiltros(); // Refaz a lista e volta pra pag 1
    });

    // Dropdowns (Isso depende de como seu CSS/HTML de dropdown funciona)
    // Assumindo que você clica no <a> dentro do dropdown-menu
    $(".dropdown-menu a").click(function(e) {
        e.preventDefault(); // Não recarregar a página
        
        // Descobre qual dropdown é (Tipo ou Raridade?)
        // Subindo até o pai para ver qual botão é
        let dropdownPai = $(this).closest(".dropdown").find(".dropdown-btn").text().trim();

        let valorSelecionado = $(this).data("value"); // outfit, legendary, etc.

        if (dropdownPai === "Tipo") {
            filtroTipo = valorSelecionado;
            console.log("Filtro Tipo:", filtroTipo);
        } else if (dropdownPai === "Raridade") {
            filtroRaridade = valorSelecionado;
            console.log("Filtro Raridade:", filtroRaridade);
        }

        aplicarFiltros();
    });

});