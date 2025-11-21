<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paginaAtual = basename($_SERVER['PHP_SELF']);

if ($paginaAtual == 'index.php') {
    $pathHome   = "index.php";
    $pathLoja   = "paginas/catalogo.php";
    $pathPerfil = "paginas/perfil.php";
    $pathLogin  = "paginas/login.php";
    $pathLogout = "backend/logout.php";
    $imgVbuck   = "img/icons/vbuck.png";
} else {
    $pathHome   = "../index.php";
    $pathLoja   = "catalogo.php";
    $pathPerfil = "perfil.php";
    $pathLogin  = "login.php";
    $pathLogout = "../backend/logout.php";
    $imgVbuck   = "../img/icons/vbuck.png";
}

if (isset($_SESSION['user_id'])) {
    $linkDoPerfil = $pathPerfil;
} else {
    $linkDoPerfil = $pathLogin;
}

$btnTexto = "Entrar";
$btnLink  = $pathLogin;

if ($paginaAtual == 'login.php' || $paginaAtual == 'cadastro.php') {
    $btnTexto = "Voltar";
    $btnLink  = $pathHome;
} elseif (isset($_SESSION['user_id'])) {
    $btnTexto = "Sair";
    $btnLink  = $pathLogout;
}
?>

<nav>
    <div class="qtd-V-bucks">
        <img src="<?php echo $imgVbuck; ?>" alt="V-bucks">
        <h2>
            <?php
            if (isset($_SESSION['user_id']) && isset($_SESSION['vbucks'])) {
                echo number_format($_SESSION['vbucks'], 0, ',', '.');
            } else {
                echo "0";
            }
            ?>
        </h2>
    </div>

    <ul class="nav-paginas">
        <li><a href="<?php echo $pathHome; ?>">Home</a></li>
        <li><a href="<?php echo $pathLoja; ?>">Loja</a></li>
        <li><a href="<?php echo $linkDoPerfil; ?>">Perfil</a></li>
    </ul>

    <button>
        <a href="<?php echo $btnLink; ?>">
            <?php echo $btnTexto; ?>
        </a>
    </button>
</nav>
