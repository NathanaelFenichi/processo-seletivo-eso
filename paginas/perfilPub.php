<?php
session_start();
require '../conecta.php';

// Pega o ID da URL (ex: perfil_publico.php?id=5)
$idAlvo = $_GET['id'] ?? 0;

if ($idAlvo == 0) {
    echo "Usuário não encontrado.";
    exit;
}

// Busca dados do usuário alvo
$stmt = $conn->prepare("SELECT nome, vbucks FROM usuarios WHERE ID = ?");
$stmt->bind_param("i", $idAlvo);
$stmt->execute();
$userAlvo = $stmt->get_result()->fetch_assoc();

if (!$userAlvo) {
    echo "Usuário não existe.";
    exit;
}

// Busca inventário dele
$stmtC = $conn->prepare("SELECT * FROM compras WHERE user_id = ? ORDER BY data DESC");
$stmtC->bind_param("i", $idAlvo);
$stmtC->execute();
$inventario = $stmtC->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Inventário de <?php echo htmlspecialchars($userAlvo['nome']); ?></title>
  <link rel="stylesheet" href="../css/geral.css">
  <link rel="stylesheet" href="../css/perfil.css"> <!-- Reusa seu CSS de perfil -->
  <script src="../backend/js/jquery-3.7.1.min.js"></script>
</head>
<body>

  <?php include 'nav.php'; ?>

  <main class="container">
    <section class="perfil-usuario" style="background: #f9f9f9;">
      <div class="avatar">
        <div class="placeholder" style="background:#3498db;"><?php echo strtoupper(substr($userAlvo['nome'], 0, 1)); ?></div>
      </div>
      <div class="info-usuario">
        <h1><?php echo htmlspecialchars($userAlvo['nome']); ?></h1>
        <p class="vbucks">Saldo: <strong><?php echo $userAlvo['vbucks']; ?></strong></p>
      </div>
    </section>

    <section class="compras">
      <h2>Cosméticos Adquiridos (<?php echo $inventario->num_rows; ?>)</h2>
      
      <div class="lista-compras">
        <?php while($item = $inventario->fetch_assoc()): ?>
            <div class="item-compra" data-api-id="<?php echo $item['cosmetic_id']; ?>">
                <img src="../img/icons/loading.gif" class="item-img" alt="...">
                <div class="item-info">
                    <h3 class="nome-item">Carregando...</h3>
                    <p class="preco">Valor: <?php echo $item['preco']; ?></p>
                </div>
            </div>
        <?php endwhile; ?>
      </div>
    </section>
  </main>

  <!-- Script para carregar imagens (Igual ao seu perfil.php) -->
  <script>
    $(document).ready(function() {
        $('.item-compra').each(function() {
            const $card = $(this);
            const idItem = $card.data('api-id');
            $.getJSON(`https://fortnite-api.com/v2/cosmetics/br/${idItem}?language=pt-BR`, function(res) {
                if(res.data) {
                    $card.find('.item-img').attr('src', res.data.images.smallIcon);
                    $card.find('.nome-item').text(res.data.name);
                }
            });
        });
    });
  </script>
</body>
</html>