<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loja - Fortnite</title>
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/shop.css" /> 
  <script src="../backend/js/jquery-3.7.1.min.js" ></script>
  <script src="../backend/js/apiteste.js" ></script>
  <script src="../backend/js/fetch_api.js"></script>
 
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

        <!-- DROPDOWN TIPO -->
        <div class="dropdown">
          <button class="dropdown-btn">Tipo</button>
          <div class="dropdown-menu">
            <a href="#" data-value="">Todos</a>
            <a href="#" data-value="outfit">Outfits</a>
            <a href="#" data-value="backpack">backpack</a>
            <a href="#" data-value="pickaxe">Pickaxes</a>
            <a href="#" data-value="glider">Gliders</a>
            <a href="#" data-value="emote">Emotes</a>
            <a href="#" data-value="wrap">Wraps</a>
          </div>
        </div>

        <!-- DROPDOWN RARIDADE -->
        <div class="dropdown">
          <button class="dropdown-btn">Raridade</button>
          <div class="dropdown-menu">
            <a href="#" data-value="">Todas</a>
            <a href="#" data-value="common">Comum</a>
            <a href="#" data-value="uncommon">Incomum</a>
            <a href="#" data-value="rare">Raro</a>
            <a href="#" data-value="epic">Épico</a>
            <a href="#" data-value="legendary">Lendário</a>
          </div>
        </div>
      </div>
    </section>



    <!-- Catálogo -->
    <section id="catalogo" class="catalogo">

      
    

    </section>

    <div class="paginação">
    <button id="prev"><- Anterior</button> <span id="numero-pagina">0</span> <button id="next">Próximo -></button>
   </div>

  </main>
</body>
</html>