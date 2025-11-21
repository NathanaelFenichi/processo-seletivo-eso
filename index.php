<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Loja Fortnite</title>

  <link rel="stylesheet" href="css/geral.css" />
  <link rel="stylesheet" href="css/index.css" />

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="backend/js/home.js"></script>
</head>
<body>

  <?php include 'paginas/nav.php'; ?>

  <main class="container">
    <section class="banner">
      <h1>Seja Bem-Vindo</h1>
      <img src="img/fortinite-banner.jpeg" alt="Fortnite Banner" />
    </section>

    <section class="novos-produtos">
      <h2>Novos produtos</h2>

      <div id="novos" class="grid-produtos">
        <p style="color: #666; font-size: 1.2rem; padding: 20px;">Carregando novidades...</p>
      </div>

    </section>
  </main>

</body>
</html>
