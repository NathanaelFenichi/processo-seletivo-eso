<?php
session_start();

// Tenta encontrar o conecta.php em lugares comuns
if (file_exists('../backend/conecta.php')) {
    require '../backend/conecta.php';
} else {
    die("Erro fatal: Arquivo de conexão não encontrado. Verifique se 'conecta.php' está na pasta raiz ou em 'backend/'.");
}

// 1. SEGURANÇA: Se não estiver logado, manda para o login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// 2. BUSCAR DADOS DO USUÁRIO
$stmt = $conn->prepare("SELECT nome, email, vbucks FROM usuarios WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$resUser = $stmt->get_result();
$user = $resUser->fetch_assoc();

// 3. BUSCAR HISTÓRICO DE COMPRAS DO BANCO
$stmtCompras = $conn->prepare("SELECT * FROM compras WHERE user_id = ? ORDER BY data DESC");
$stmtCompras->bind_param("i", $userId);
$stmtCompras->execute();
$historico = $stmtCompras->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perfil - Loja Fortnite</title>
  
  <link rel="stylesheet" href="../css/geral.css" />
  <link rel="stylesheet" href="../css/perfil.css" />
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

  <?php include 'nav.php'; ?>
  
  <main class="container">
    
    <!-- CABEÇALHO DO PERFIL -->
    <section class="perfil-usuario">
      <div class="avatar">
        <div class="placeholder"><?php echo strtoupper(substr($user['nome'], 0, 1)); ?></div>
      </div>
      <div class="info-usuario">
        <h1><?php echo htmlspecialchars($user['nome']); ?></h1>
        <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
        <p class="vbucks">
          <img src="../img/icons/vbuck.png" alt="V-bucks"> 
          <strong><?php echo number_format($user['vbucks'], 0, ',', '.'); ?></strong>
        </p>
      </div>
    </section>

    <!-- LISTA DE COMPRAS -->
    <section class="compras">
      <h2>Meus Itens</h2>

      <div class="lista-compras">
        <?php if ($historico->num_rows > 0): ?>
            <?php while($item = $historico->fetch_assoc()): ?>
                <div class="item-compra" id="compra-<?php echo $item['id']; ?>" data-api-id="<?php echo $item['cosmetic_id']; ?>">
                  <img src="../img/icons/loading.gif" alt="Carregando..." class="item-img">
                  <div class="item-info">
                    <h3 class="nome-item">Carregando...</h3>
                    <p class="tipo">Item Comprado</p>
                    <p class="preco">Valor Pago: <strong><?php echo $item['preco']; ?></strong></p>
                  </div>
                  <div class="item-data">
                    <p>Comprado em:</p>
                    <p class="data"><?php echo date('d/m/Y', strtotime($item['data'])); ?></p>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <a href="produto.php?id=<?php echo $item['cosmetic_id']; ?>" class="btn-saiba-mais" style="text-decoration:none; padding:8px 12px; font-size:13px;">Ver</a>
                        <button onclick="devolverItem(<?php echo $item['id']; ?>)" style="background:#e74c3c; color:white; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-weight:bold; font-size:13px;">Devolver</button>
                    </div>
                  </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align:center; width:100%; padding:40px; color:#777;">
                <h3>Nenhum item encontrado.</h3>
                <p>Visite a <a href="../index.php" style="color:#33CC99; font-weight:bold;">Loja</a> para gastar seus V-Bucks!</p>
            </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script>
    $(document).ready(function() {
        // Carregar Imagens
        $('.item-compra').each(function() {
            const $card = $(this);
            const idItem = $card.data('api-id');
            if(idItem.startsWith('bundle_')) {
                $card.find('.nome-item').text(idItem.replace('bundle_', '').replace(/_/g, ' '));
                $card.find('.item-img').attr('src', '../img/sem-imagem.png');
                return;
            }
            $.getJSON(`https://fortnite-api.com/v2/cosmetics/br/${idItem}?language=pt-BR`, function(res) {
                if(res.data) {
                    $card.find('.item-img').attr('src', res.data.images.smallIcon || res.data.images.icon);
                    $card.find('.nome-item').text(res.data.name);
                    if(res.data.type) $card.find('.tipo').text(res.data.type.displayValue);
                }
            });
        });
    });

    // Função Devolver
    function devolverItem(idCompra) {
        if(!confirm("Deseja devolver este item e recuperar seus V-Bucks?")) return;
        $.ajax({
            url: '../backend/devolver.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id_compra: idCompra }),
            success: function(res) {
                if(res.sucesso) {
                    alert("Sucesso: " + res.msg);
                    location.reload();
                } else {
                    alert("Erro: " + res.msg);
                }
            },
            error: function() { alert("Erro ao comunicar com o servidor."); }
        });
    }
  </script>
</body>
</html>