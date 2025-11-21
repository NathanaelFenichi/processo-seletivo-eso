<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paginaAtual = basename($_SERVER['PHP_SELF']);

// --- 1. DEFINIÇÃO DE CAMINHOS ---
if ($paginaAtual == 'index.php') {
    // Na Raiz (Home)
    $pathHome   = "index.php";
    $pathLoja   = "paginas/catalogo.php";
    $pathPerfil = "paginas/perfil.php";
    $pathLogin  = "paginas/login.php";
    $pathLogout = "backend/logout.php";
    $imgVbuck   = "img/icons/vbuck.png";
} else {
    // Dentro da pasta 'paginas'
    $pathHome   = "../index.php";
    $pathLoja   = "catalogo.php";
    $pathPerfil = "perfil.php";
    $pathLogin  = "login.php";
    $pathLogout = "../backend/logout.php";
    $imgVbuck   = "../img/icons/vbuck.png";
}

// --- 2. LÓGICA DO LINK "PERFIL" ---
// Se logado -> vai pro Perfil. Se deslogado -> vai pro Login.
if (isset($_SESSION['user_id'])) {
    $linkDoPerfil = $pathPerfil;
} else {
    $linkDoPerfil = $pathLogin;
}

// --- 3. LÓGICA DO BOTÃO (Voltar / Sair / Entrar) ---
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
            // Verifica se está logado E se tem vbucks na sessão
            if (isset($_SESSION['user_id']) && isset($_SESSION['vbucks'])) {
                echo number_format($_SESSION['vbucks'], 0, ',', '.');
            } else {
                // Se não estiver logado, mostra 0
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