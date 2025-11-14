
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

  <!-- Navbar -->
 <nav>
    <div class="qtd-V-bucks">
      <img src="../img/icons/vbuck.png" alt="V-bucks">
      <h2>0</h2>
    </div>
    <ul class="nav-paginas">
      <li><a href="../index.php">Home</a></li>
      <li><a href="paginas.html">Loja</a></li>
      <li><a href="perfil.html">Perfil</a></li>
    </ul>
    <button><a href="../index.php">voltar</a></button>
  </nav>

  <main class="container">
    <div class="form-card">
      <h1>FAÇA LOGIN</h1>
      <p class="subtitle">Entre com suas credenciais para continuar.</p>

      <form method="post" action="../backend/validaLogin.php">
        <label>Digite seu email</label>

        <div class="input-group">
          <span class="icon"><img src="../img/icons/email 1.png" alt=""></span>
          <input name="email" type="email" placeholder="email@example.com" required />
        </div>

        <label>Senha</label>
        <div class="input-group">
          <span class="icon"><img src="../img/icons/padlock 5.png" alt=""></span>
          <input name="senha" type="password" placeholder="Digite sua senha..." required />
        </div>

        <button type="submit" class="btn-submit">Entrar</button>

        <p class="switch-link">Não tem conta? <a href="../cadastro.html">Cadastre-se!</a></p>
      </form>
    </div>
  </main>
</body>
</html>