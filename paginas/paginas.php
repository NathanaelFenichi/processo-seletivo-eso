<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loja - Fortnite</title>
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/shop.css" />
</head>
<body>

  <!-- Navbar -->

  <?php
 include 'nav.php';
  ?>
  
  <main class="container">



    <!-- Pesquisa + Filtros -->
    <section class="pesquisa-e-filtros">
      <div class="barra-Pesquisa">
        <label for="pesquisa">
          <img src="../img/icons/pesquisa.png" alt="Buscar">
        </label>
        <input type="search" id="pesquisa" placeholder="Pesquise por skins, armas, emotes..." />

        <div class="dropdown">
          <button class="dropdown-btn">Tipo</button>
          <div class="dropdown-menu">
            <a href="#">Outfits</a>
            <a href="#">Back Blings</a>
            <a href="#">Pickaxes</a>
            <a href="#">Gliders</a>
            <a href="#">Emotes</a>
            <a href="#">Wraps</a>
          </div>
        </div>

        <div class="dropdown">
          <button class="dropdown-btn">Raridade</button>
          <div class="dropdown-menu">
            <a href="#">Comum</a>
            <a href="#">Incomum</a>
            <a href="#">Raro</a>
            <a href="#">Épico</a>
            <a href="#">Lendário</a>
          </div>
        </div>
      </div>
    </section>



    <!-- Catálogo -->
    <section class="catalogo">

      <!-- CARD 1: NOVO -->
      <a href="produto.html" class="card-link">
        <div class="card">
          <img src="../img/fortinite-banner.jpeg" class="card-img" alt="Skin">
          <div class="card-body">
            <div class="card-div">
              <div class="titulo">
                <p class="tipo">Outfit</p>
                <h5 class="card-name">Ochaco Uraraka</h5>
              </div>
              <div class="badge-novo">NOVO</div>
            </div>
            <hr>
            <div class="card-div">
              <p class="valor">V-bucks: <span>1.500</span></p>
              <p class="raridade">Lendário</p>
            </div>
            <button class="btn-comprar">Comprar</button>
          </div>
        </div>
      </a>

      <!-- CARD 2: À VENDA -->
      <a href="produto.html" class="card-link">
        <div class="card">
          <img src="../img/arma.png" class="card-img" alt="Arma">
          <div class="card-body">
            <div class="card-div">
              <div class="titulo">
                <p class="tipo">Arma</p>
                <h5 class="card-name">Plasma Rifle</h5>
              </div>
              <div class="badge-venda">À VENDA</div>
            </div>
            <hr>
            <div class="card-div">
              <p class="valor">V-bucks: <span>800</span></p>
              <p class="raridade">Épico</p>
            </div>
            <button class="btn-comprar">Comprar</button>
          </div>
        </div>
      </a>

      <!-- CARD 3: ADQUIRIDO -->
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

      <!-- CARD 4: NORMAL -->
      <a href="produto.html" class="card-link">
        <div class="card">
          <img src="../img/arma.png" class="card-img" alt="Emote">
          <div class="card-body">
            <div class="card-div">
              <div class="titulo">
                <p class="tipo">Emote</p>
                <h5 class="card-name">Floss</h5>
              </div>
            </div>
            <hr>
            <div class="card-div">
              <p class="valor">V-bucks: <span>500</span></p>
              <p class="raridade">Raro</p>
            </div>
            <button class="btn-comprar">Comprar</button>
          </div>
        </div>
      </a>

      <!-- Adicione mais cards aqui -->

    </section>
      </main>

      <!-- Scripts: jQuery + catalogo e filtros (local backend/js) -->
      <script src="../backend/js/jquery-3.7.1.min.js"></script>
      <script src="../backend/js/catalogo.js"></script>
      <script src="../backend/js/filtros.js"></script>
</body>
</html>