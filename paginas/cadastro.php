<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastre-se - Loja Fortnite</title>
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/cadastro-login.css" />
</head>
<body>

  <?php include 'nav.php'; ?>

  <main class="container">
    <div class="form-card">
      <h1>CADASTRE-SE</h1>

      <?php
      if (isset($_SESSION['cadastro_erro'])) {
          echo "<p style='color: #ff4444; font-weight: bold; text-align: center; margin: 10px 0;'>" . $_SESSION['cadastro_erro'] . "</p>";
          unset($_SESSION['cadastro_erro']);
      }
      ?>

      <p class="subtitle">Insira seus dados para acessar nosso sistema.</p>

      <form method="post" action="../backend/validaCadastro.php">
        
        <label>Nome</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/user 1.png" alt=""></span>
          <input name="nome" type="text" placeholder="Nome completo" required />
        </div>

        <label>Email</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/email 1.png" alt=""></span>
          <input name="email" type="email" placeholder="email@example.com" required />
        </div>

        <label>Senha</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/padlock 5.png" alt=""></span>
          <input name="senha" type="password" placeholder="Crie uma senha" required />
        </div>

        <label>Confirmar senha</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/padlock 5.png" alt=""></span>
          <!-- Importante: name="confirmaSenha" deve ser igual ao que o PHP espera -->
          <input name="confirmaSenha" type="password" placeholder="Repita a senha" required />
        </div>

        <button type="submit" class="btn-submit">Cadastrar</button>

        <p class="switch-link">Já tem conta? <a href="login.php">Faça login!</a></p>
      </form>
    </div>
  </main>
</body>
</html>