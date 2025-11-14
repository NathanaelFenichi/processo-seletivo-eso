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
    <section id="catalogo" class="catalogo">

      
    

    </section>

    <div class="paginação">
    <button id="prev"><- Anterior</button> <span id="numero-pagina">0</span> <button id="next">Próximo -></button>
   </div>

  </main>
</body>
</html>