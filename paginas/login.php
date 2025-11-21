<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Loja Fortnite</title>

  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/cadastro-login.css" />
</head>
<body>


  <?php include 'nav.php'; ?>

  <main class="container">
    <div class="form-card">
      <h1>FAÇA LOGIN</h1>

      <?php
      if (isset($_SESSION['login_erro'])) {
          echo "<p style='color: #ff4444; font-weight: bold; margin: 10px 0; text-align: center;'>" . $_SESSION['login_erro'] . "</p>";
          unset($_SESSION['login_erro']);
      }
      ?>

      <p class="subtitle">Entre com suas credenciais para continuar.</p>

      <form method="post" action="../backend/validaLogin.php">
        <label>Digite seu email</label>

        <div class="input-group">
          <span class="icon"><img src="../img/icons/email 1.png" alt="Email Icon"></span>
          <input name="email" type="email" placeholder="email@example.com" required />
        </div>

        <label>Senha</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/padlock 5.png" alt="Senha Icon"></span>
          <input name="senha" type="password" placeholder="Digite sua senha..." required />
        </div>

        <button type="submit" class="btn-submit">Entrar</button>

        <p class="switch-link">Não tem conta? <a href="cadastro.php">Cadastre-se!</a></p>
      </form>
    </div>
  </main>
</body>
</html>