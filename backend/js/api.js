$(document).ready(function() {

   "https://fortnite-api.com/v2/cosmetics/br";
    let itens = [];
    let pagina = 0;
    const porPagina = 10;

    function mostrarPagina() {
      const inicio = pagina * porPagina;
      const fim = inicio + porPagina;
      const parte = itens.slice(inicio, fim);
      let html = "";

      parte.forEach(item => {
        html += `


        <a href="produto.html" class="card-link">
        <div class="card">
          <img src="../img/images 1.png" class="card-img" alt="Skin Adquirida">
          <div class="card-body">
            <div class="card-div">
              <div class="titulo">
                <p class="tipo">Outfit</p>
                <h5 class="card-name">Dark Voyager</h5>
              </div>
            </div>
            <hr>
            <div class="card-div">
              <p class="valor">V-bucks: <span>600</span></p>
              <p class="raridade">Lendário</p>
            </div>
            <button class="btn-adquirido">Adquirido</button>
          </div>
        </div>
      </a>


          <div>
            <img src="${item.images.icon}" width="80">
            <p>${item.name}</p>
            <small>${item.rarity.value}</small>
          </div>
        `;
      });

      $("#lista").html(html);
    }

    $.getJSON(url, function(resposta) {
      itens = resposta.data;
      mostrarPagina();
    });

    $("#next").click(function() {
      if ((pagina + 1) * porPagina < itens.length) {
        pagina++;
        mostrarPagina();
      }
    });

    $("#prev").click(function() {
      if (pagina > 0) {
        pagina--;
        mostrarPagina();
      }
    });
  });