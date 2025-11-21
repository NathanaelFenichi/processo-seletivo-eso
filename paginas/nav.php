<?php
// Só inicia a sessão se ela ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica para saber se estamos na raiz ou na pasta 'paginas/'
$isInPages = strpos($_SERVER['REQUEST_URI'], '/paginas/') !== false;

// Define os caminhos relativos corretos
$pathHome = $isInPages ? "../index.php" : "index.php";
$pathLoja = $isInPages ? "catalogo.php" : "paginas/catalogo.php";
$pathPerfil = $isInPages ? "perfil.php" : "paginas/perfil.php";
$pathLogin = $isInPages ? "login.php" : "paginas/login.php";
$pathLogout = $isInPages ? "../backend/logout.php" : "backend/logout.php";

// Caminho da imagem do V-Bucks
$imgVbuck = $isInPages ? "../img/icons/vbuck.png" : "img/icons/vbuck.png";
?>

<nav>
  <div class="qtd-V-bucks">
    <img src="<?php echo $imgVbuck; ?>" alt="V-bucks">
    <?php 
    if(isset($_SESSION['vbucks'])) {
        echo "<h2>" . number_format($_SESSION['vbucks'], 0, ',', '.') . "</h2>";
    } else {
        echo "<h2>0</h2>";
    }
    ?>
  </div>

  <ul class="nav-paginas">
    <li><a href="<?php echo $pathHome; ?>">Home</a></li>
    <li><a href="<?php echo $pathLoja; ?>">Loja</a></li>
    <li><a href="<?php echo $pathPerfil; ?>">Perfil</a></li>
  </ul>

  <!-- Botão Dinâmico com a estrutura HTML original para manter o CSS -->
  <?php if(isset($_SESSION['user_id'])): ?>
      <!-- Logado: Botão Sair -->
      <button><a href="<?php echo $pathLogout; ?>">Sair</a></button>
  <?php else: ?>
      <!-- Deslogado: Botão Entrar -->
      <button><a href="<?php echo $pathLogin; ?>">Entrar</a></button>
  <?php endif; ?>
</nav>