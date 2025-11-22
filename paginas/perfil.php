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
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

  <?php include 'nav.php'; ?>
  
  <main class="container">
    
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
        // --- CARREGAMENTO DE IMAGENS ---
        $('.item-compra').each(function() {
            const $card = $(this);
            const idItem = $card.data('api-id');
            const $img = $card.find('.item-img');
            const $nome = $card.find('.nome-item');
            const $tipo = $card.find('.tipo');

            if (String(idItem).startsWith('bundle_')) {
                let nomeLimpo = idItem.replace('bundle_', '').replace(/_/g, ' ');
                $nome.text(nomeLimpo);
                $tipo.text("Pacote Promocional");
                $img.attr('src', 'https://fortnite-api.com/images/cosmetics/br/bid_001_generic/icon.png'); 
                return;
            }

            $.ajax({
                url: `https://fortnite-api.com/v2/cosmetics/br/${idItem}?language=pt-BR`,
                dataType: 'json',
                success: function(res) {
                    if(res.data) {
                        const imagemFinal = res.data.images.smallIcon || res.data.images.icon;
                        $img.attr('src', imagemFinal);
                        $nome.text(res.data.name);
                        if(res.data.type) $tipo.text(res.data.type.displayValue);
                    }
                },
                error: function() {
                    $nome.text("Item Indisponível (API)");
                    $img.attr('src', 'https://fortnite-api.com/images/cosmetics/br/bid_001_generic/icon.png'); 
                }
            });
        });
    });

    // --- FUNÇÃO DEVOLVER (CORRIGIDA) ---
    function devolverItem(idCompra) {
        if(!confirm("Tem certeza? Essa ação removerá o item e devolverá os V-Bucks.")) return;

        const $btn = $(`#compra-${idCompra} button`);
        const textoOriginal = $btn.text();
        $btn.text("Processando...").prop("disabled", true);

        $.ajax({
            url: '/backend/devolver.php',
            method: 'POST',
            
            // CORREÇÃO CRÍTICA AQUI:
            // Avisa o servidor que estamos mandando JSON
            contentType: 'application/json', 
            
            // Transforma o objeto JavaScript em texto JSON string
            data: JSON.stringify({ id_compra: idCompra }), 
            
            success: function(res) {
                if(res.sucesso) {
                    alert("✅ " + res.msg);
                    $(`#compra-${idCompra}`).fadeOut(500, function(){ $(this).remove(); });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert("❌ Erro: " + (res.msg || "Erro desconhecido"));
                    $btn.text(textoOriginal).prop("disabled", false);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); 
                alert("Erro de sistema: " + xhr.status);
                $btn.text(textoOriginal).prop("disabled", false);
            }
        });
    }
  </script>
</body>
</html>