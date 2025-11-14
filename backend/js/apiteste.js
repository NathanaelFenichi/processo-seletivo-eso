$(document).ready(function() {
  let todosItens = [];
  let itensNovos = new Set();
  let itensShop = {};
  let itensAdquiridos = new Set(); // Simulado por agora
  let itensFiltrados = [];
  let pagina = 0;
  const porPagina = 12;

  let filtros = {
    search: '', type: '', rarity: '', dateStart: '', dateEnd: '',
    onlyNew: false, onlySale: false, onlyPromo: false
  };

  console.log("🚀 Fortnite Shop carregado!");

  // LOADING
  $('#catalogo').html('<div class="loading">Carregando cosméticos...</div>');

  // 1. CARREGA TODOS OS COSMÉTICOS
  $.getJSON('https://fortnite-api.com/v2/cosmetics/br', function(data) {
    todosItens = data.data;
    console.log(`✅ ${todosItens.length} itens carregados`);

    // 2. CARREGA ITENS NOVOS
    $.getJSON('https://fortnite-api.com/v2/cosmetics/new', function(newData) {
      (newData.data.items.br || []).forEach(item => itensNovos.add(item.id));
      console.log(`⭐ ${itensNovos.size} itens NOVOS`);

      // 3. CARREGA SHOP (À VENDA)
      $.getJSON('https://fortnite-api.com/v2/shop', function(shopData) {
        ['featured', 'daily'].forEach(section => {
          (shopData.data[section]?.entries || []).forEach(entry => {
            if (!entry.brItems) return;
            const price = entry.finalPrice;
            const regular = entry.regularPrice || price;
            const isPromo = price < regular;
            const bundleItems = entry.brItems.map(item => item.id);

            entry.brItems.forEach(item => {
              itensShop[item.id] = { price, regular, isPromo, bundleItems };
            });
          });
        });
        console.log(`🛒 ${Object.keys(itensShop).length} itens À VENDA`);

        aplicarFiltros();
        mostrarPagina();
      }).fail(() => console.log('Shop offline - preços default'));
    }).fail(() => console.log('Novos offline'));
  }).fail(() => $('#catalogo').html('❌ Erro na API - recarregue'));

  // APLICA TODOS OS FILTROS
  function aplicarFiltros() {
    itensFiltrados = todosItens.filter(item => {
      // Busca por nome
      const nameMatch = !filtros.search || item.name.toLowerCase().includes(filtros.search.toLowerCase());
      
      // Filtro por tipo
      const typeMatch = !filtros.type || item.type.value === filtros.type;
      
      // Filtro por raridade
      const rarityMatch = !filtros.rarity || item.rarity.value === filtros.rarity;
      
      // Filtro por data
      const addedDate = new Date(item.added || '1970-01-01');
      const dateStart = filtros.dateStart ? new Date(filtros.dateStart) : null;
      const dateEnd = filtros.dateEnd ? new Date(filtros.dateEnd + 'T23:59:59') : null;
      const dateMatch = (!dateStart || addedDate >= dateStart) && (!dateEnd || addedDate <= dateEnd);
      
      // Filtros especiais
      const newMatch = !filtros.onlyNew || itensNovos.has(item.id);
      const saleMatch = !filtros.onlySale || !!itensShop[item.id];
      const promoMatch = !filtros.onlyPromo || itensShop[item.id]?.isPromo;

      return nameMatch && typeMatch && rarityMatch && dateMatch && newMatch && saleMatch && promoMatch;
    });

    pagina = 0;
    mostrarPagina();
  }

  // MOSTRA PÁGINA COM ÍCONES PNG
  function mostrarPagina() {
    const inicio = pagina * porPagina;
    const fim = inicio + porPagina;
    let html = '';

    for (let i = inicio; i < fim && i < itensFiltrados.length; i++) {
      const item = itensFiltrados[i];
      const shopInfo = itensShop[item.id] || {};
      const price = shopInfo.price || calcularPreco(item);
      const bundleCount = shopInfo.bundleItems?.length || 1;
      const buyText = shopInfo.bundleItems ? `Bundle (${bundleCount})` : 'Comprar';

      // ÍCONES PNG - SEUS ARQUIVOS!
      let badges = '';
      if (itensNovos.has(item.id)) {
        badges += `<img src="../img/icon/icon-novo.png" class="badge-icon" alt="Novo" title="Novo!">`;
      }
      if (itensShop[item.id]) {
        badges += `<img src="../img/icon/venda.png" class="badge-icon" alt="À Venda" title="À Venda">`;
      }
      if (itensAdquiridos.has(item.id)) {
        badges += `<img src="../img/icon/Adquirido.png" class="badge-icon" alt="Adquirido" title="Comprado">`;
      }

      html += `
        <a href="produto.html?id=${item.id}" class="card-link">
          <div class="card">
            ${badges ? `<div class="badges">${badges}</div>` : ''}
            <img src="${item.images.icon}" class="card-img" alt="${item.name}" onerror="this.src='../img/placeholder.png'">
            <div class="card-body">
              <div class="card-div">
                <div class="titulo">
                  <p class="tipo">${item.type.value}</p>
                  <h5 class="card-name">${item.name}</h5>
                </div>
              </div>
              <hr>
              <div class="card-div">
                <p class="valor">V-bucks: <span>${price}</span></p>
                <p class="raridade">${item.rarity.value}</p>
              </div>
              <button class="btn-comprar" data-id="${item.id}" data-price="${price}">${buyText}</button>
            </div>
          </div>
        </a>
      `;
    }

    $('#catalogo').html(html || '<p>Nenhum item encontrado. Ajuste os filtros! 🔍</p>');
    $('#numero-pagina').text(`${pagina + 1} / ${Math.ceil(itensFiltrados.length / porPagina)}`);

    // BIND BOTÕES COMPRAR
    $('.btn-comprar').off('click').on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const id = $(this).data('id');
      const price = $(this).data('price');
      
      // SIMULA COMPRA (integre com seu backend depois)
      itensAdquiridos.add(id);
      alert(`✅ Comprado por ${price} V-Bucks!`);
      mostrarPagina(); // Refresh badges
    });
  }

  // CALCULA PREÇO POR RARIDADE
  function calcularPreco(item) {
    const prices = { 
      common: 200, uncommon: 500, rare: 800, 
      epic: 1200, legendary: 1500, mythic: 2000 
    };
    let base = prices[item.rarity.value] || 500;
    if (item.type.value === 'outfit') base *= 1.5;
    return Math.round(base);
  }

  // EVENTOS DOS FILTROS (LIVE)
  $('#pesquisa').on('input', function() {
    filtros.search = $(this).val();
    aplicarFiltros();
  });

  $('#tipo, #raridade').on('change', function() {
    filtros[$(this).attr('id')] = $(this).val();
    aplicarFiltros();
  });

  $('#date-start, #date-end').on('change', function() {
    filtros[$(this).attr('id')] = $(this).val();
    aplicarFiltros();
  });

  $('#only-new, #only-sale, #only-promo').on('change', function() {
    filtros[this.id.replace('only-', 'only')] = $(this).is(':checked');
    aplicarFiltros();
  });

  // PAGINAÇÃO
  $('#next').click(function() {
    if ((pagina + 1) * porPagina < itensFiltrados.length) {
      pagina++;
      mostrarPagina();
    }
  });

  $('#prev').click(function() {
    if (pagina > 0) {
      pagina--;
      mostrarPagina();
    }
  });
});