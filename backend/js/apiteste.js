$(document).ready(function() {
  let todosItens = [];
  let itensFiltrados = [];
  let pagina = 0;
  const porPagina = 12;

  let filtroTipo = '';
  let filtroRaridade = '';
  let termoBusca = '';

  // CARREGA API
  $.getJSON("https://fortnite-api.com/v2/cosmetics/br", function(data) {
    todosItens = data.data;
    itensFiltrados = todosItens;
    mostrarPagina();
    console.log("API carregada: " + todosItens.length + " itens");
  }).fail(function() {
    $('#catalogo').html("<p style='color:red'>Erro na API</p>");
  });

  // PESQUISA AO VIVO
  $('#pesquisa').on('input', function() {
    termoBusca = $(this).val().toLowerCase().trim();
    aplicarFiltros();
  });

  // FILTROS POR CLIQUE (dropdowns)
  $('.dropdown-menu a').on('click', function(e) {
    e.preventDefault();
    const valor = $(this).data('value');
    const texto = $(this).text();
    const botao = $(this).closest('.dropdown').find('.dropdown-btn');

    // Atualiza botão
    botao.text(texto);

    // Atualiza filtro
    if ($(this).closest('.dropdown').find('.dropdown-btn').text().includes('Tipo')) {
      filtroTipo = valor;
    } else {
      filtroRaridade = valor;
    }

    aplicarFiltros();
  });

  // APLICA TODOS OS FILTROS
  function aplicarFiltros() {
    itensFiltrados = todosItens.filter(item => {
      const nomeMatch = !termoBusca || item.name.toLowerCase().includes(termoBusca);
      const tipoMatch = !filtroTipo || item.type.value === filtroTipo;
      const raridadeMatch = !filtroRaridade || item.rarity.value === filtroRaridade;
      return nomeMatch && tipoMatch && raridadeMatch;
    });
    pagina = 0;
    mostrarPagina();
  }

  // MOSTRA PÁGINA
  function mostrarPagina() {
    const inicio = pagina * porPagina;
    const fim = inicio + porPagina;
    let html = '';

    for (let i = inicio; i < fim && i < itensFiltrados.length; i++) {
      const item = itensFiltrados[i];
      const preco = item.rarity.value === "rare" ? 800 : 
                    item.rarity.value === "epic" ? 1200 : 500;

      html += `
        <a href="produto.html" class="card-link">
          <div class="card">
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
                <p class="valor">V-bucks: <span>${preco}</span></p>
                <p class="raridade">${item.rarity.value}</p>
              </div>
              <button class="btn-comprar">Comprar</button>
            </div>
          </div>
        </a>
      `;
    }

    $('#catalogo').html(html || "<p>Nenhum item encontrado.</p>");
    $('#numero-pagina').text(`${pagina + 1} / ${Math.ceil(itensFiltrados.length / porPagina)}`);
  }

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