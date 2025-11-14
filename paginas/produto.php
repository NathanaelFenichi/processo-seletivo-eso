<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nome da Skin - Loja</title>
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/produto.css" />
  <script src="../backend/js/jquery-3.7.1.min.js"></script>
  <script src="../backend/js/produto.js"></script>
</head>
<body>
  
  <?php include 'nav.php'; ?>

  <main class="container">
    <div class="produto-wrapper">

      <!-- Imagem -->
      <div class="skin-imagem">
        <img id="imagem" src="../img/arma.png" alt="Nome da Skin" />
        <div class="logo-fortnite">FORTNITE</div>
      </div>

      <!-- Infos -->
      <div class="skin-info">
        <h1 class="nome-skin" id="nome">Nome da Skin</h1>
        <p class="tipo" id="tipo">Tipo: <span>Outfit</span></p>
        <p class="raridade" id="raridade">Raridade: <span>Lendário</span></p>
        <p class="preco" id="preco">V-bucks: <strong>600</strong></p>

        <div class="botoes">
          <button class="btn-comprar">Comprar</button>
          <button class="btn-voltar">Voltar</button>
        </div>
      </div>
    </div>

    <!-- Descrição -->
    <div class="descricao">
      <h2>Descrição</h2>
      <p id="descricao">Descrição do item...</p>
    </div>
  </main>
</body>
</html>