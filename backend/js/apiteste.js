$(document).ready(function() {
  let itens = [];
  let pagina = 0;
  const porPagina = 12;
   let numeroPagina = 0;

  console.log("Script carregado com sucesso.");

  $.getJSON("https://fortnite-api.com/v2/cosmetics/br", function(catalogo) {
    itens = catalogo.data;
    console.log("Itens recebidos:", itens.length);
    mostrarPagina();
  });

  function mostrarPagina() {
    const inicio = pagina * porPagina;
    const fim = inicio + porPagina;
    let produto = '';

    for (let i = inicio; i < fim && i < itens.length; i++) {
      const item = itens[i];

      produto += `
        <a href="produto.html" class="card-link">
          <div class="card">
            <img src="${item.images.icon}" class="card-img" alt="${item.name}">
            <div class="card-body">
              <div class="card-div">
                <div class="titulo">
                  <p class="tipo">${item.type.value}</p>
                  <h5 class="card-name">${item.name}</h5>
                </div>
              </div>
              <hr>
              <div class="card-div">
                <p class="valor">V-bucks: <span>500</span></p>
                <p class="raridade">${item.rarity.value}</p>
              </div>
              <button class="btn-comprar">Comprar</button>
            </div>
          </div>
        </a>
      `;
    }

    $('#catalogo').html(produto);
  }

    $('#next').click(function() {
        if ((pagina +1)* porPagina < itens.length) {
            pagina++;
            numeroPagina++;
            mostrarPagina();
            $('#numero-pagina').text(numeroPagina);
        }
    });

    $('#prev').click(function() {
        if (pagina > 0) {
            pagina--;
            numeroPagina--;
            mostrarPagina();
              $('#numero-pagina').text(numeroPagina);
        }
    });
 
});
