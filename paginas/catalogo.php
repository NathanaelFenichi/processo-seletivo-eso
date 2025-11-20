<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loja Fortnite - Preview Completo</title>
  
  <!-- Importando CSS Externo -->
  <link rel="stylesheet" href="../css/shop.css">
  <link rel="stylesheet" href="../css/geral.css">

  <!-- Importando os js -->
  <script src="../backend/js/jquery-3.7.1.min.js"></script>
  <script src="../backend/js/catalogo.js"></script>
</head>
<body>
<!-- Importa a Nav- bar -->
  <?php include 'nav.php'; ?>

  <main class="container">
    <section class="pesquisa-e-filtros">
      <div class="barra-Pesquisa">
        <label for="pesquisa">🔍</label>
        <input type="search" id="pesquisa" placeholder="Pesquise por skins, picaretas..." />

        <!-- Dropdown TIPO -->
        <div class="dropdown" id="dropdown-tipo">
          <button class="dropdown-btn">Tipo</button>
          <div class="dropdown-menu">
            <a href="#" data-value="">Todos</a>
            <a href="#" data-value="outfit">Trajes (Skins)</a>
            <a href="#" data-value="backpack">Mochilas</a>
            <a href="#" data-value="pickaxe">Picaretas</a>
            <a href="#" data-value="emote">Gestos (Emotes)</a>
            <a href="#" data-value="glider">Asa-delta</a>
            <a href="#" data-value="music">Músicas</a>
            <a href="#" data-value="car">Carros</a>
          </div>
        </div>

        <!-- Dropdown RARIDADE -->
        <div class="dropdown" id="dropdown-raridade">
          <button class="dropdown-btn">Raridade</button>
          <div class="dropdown-menu">
            <a href="#" data-value="">Todas</a>
            <a href="#" data-value="common">Comum</a>
            <a href="#" data-value="uncommon">Incomum</a>
            <a href="#" data-value="rare">Raro</a>
            <a href="#" data-value="epic">Épico</a>
            <a href="#" data-value="legendary">Lendário</a>
            <a href="#" data-value="icon">Série Ícones</a>
            <a href="#" data-value="marvel">Marvel</a>
          </div>
        </div>
      </div>

      <div class="filtros-extra">
        <!-- Checkboxes -->
        <label class="checkbox-pill">
            <input type="checkbox" id="filter-shop">
            Na Loja
        </label>
        <label class="checkbox-pill">
            <input type="checkbox" id="filter-new">
            Novos
        </label>
      </div>
    </section>

    <section id="catalogo" class="catalogo"></section>

    <div class="paginação">
        <button id="prev" class="btn-pag">❮ Anterior</button>
        <span id="numero-pagina" class="num-pag">0 / 0</span>
        <button id="next" class="btn-pag">Próximo ❯</button>
    </div>
  </main>

  <!-- Importando Script Externo -->
  <script src="catalogo.js"></script>

</body>
</html>