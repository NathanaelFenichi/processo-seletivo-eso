<?php
session_start();
require '../backend/conecta.php'; 

if ($conn->connect_error) {
    die("Erro: Conexão com BD falhou.");
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$query = "SELECT id, email, vbucks FROM usuarios ORDER BY id DESC LIMIT $per_page OFFSET $offset";
$result = $conn->query($query);

$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['ultima_compra'] = "Nenhuma compra registrada"; 
        $row['data_compra'] = "--/--/----";
        $users[] = $row;
    }
}

$count_query = "SELECT COUNT(*) as total FROM usuarios";
$count_result = $conn->query($count_query);
$total = $count_result ? $count_result->fetch_assoc()['total'] : 0;
$pages = ceil($total / $per_page);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Loja Fortnite</title>
    <link rel="stylesheet" href="../css/shop.css">  
    <link rel="stylesheet" href="../css/geral.css">
    <link rel="stylesheet" href="../css/usuarios.css"> 
</head>
<body>
    <?php include 'nav.php'; ?>

    <main class="container">
        <h1>Usuários Cadastrados</h1>
        
        <?php if (empty($users)): ?>
            <p class="aviso-vazio">Nenhum usuário cadastrado.</p>
        <?php else: ?>
            
            <ul class="user-list"> 
                <?php foreach ($users as $user): ?>
                    <li>
                        <span class="user-check">#<?= $user['id'] ?></span>
                        
                        <span><?= htmlspecialchars($user['email']) ?></span>
                        
                        <span class="user-vbucks"><?= number_format($user['vbucks'], 0, ',', '.') ?> V-Bucks</span>
                        
                        <button 
                            class="user-link btn-ver-perfil"
                            data-id="<?= $user['id'] ?>"
                            data-email="<?= htmlspecialchars($user['email']) ?>"
                            data-vbucks="<?= $user['vbucks'] ?>"
                            data-ultima-compra="<?= htmlspecialchars($user['ultima_compra']) ?>"    
                            data-data-compra="<?= $user['data_compra'] ?>">
                            Ver Perfil
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>

        <div class="paginacao-container" style="margin-top: 20px; text-align: center;">
            <button id="prev" class="btn-pag" <?= $page <= 1 ? 'disabled' : '' ?>>❮ Anterior</button>
            <span class="num-pag" style="margin: 0 15px;">Página <?= $page ?> de <?= $pages ?></span>
            <button id="next" class="btn-pag" <?= $page >= $pages ? 'disabled' : '' ?>>Próximo ❯</button>
        </div>
    </main>

    <div id="modalPerfil" class="modal-container">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            
            <div class="modal-header">
                <h2>Perfil do Usuário <span id="modal-id" style="font-size: 0.6em; color: #777;"></span></h2>
            </div>
            
            <div class="modal-body">
                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                <p><strong>Saldo Atual:</strong> <span id="modal-vbucks" style="color: #00bcd4; font-weight: bold;"></span> V-Bucks</p>
                
                <div class="compra-box">
                    <h4>Histórico Recente</h4>
                    <p><strong>Último Item:</strong> <span id="modal-item"></span></p>
                    <p><strong>Data:</strong> <span id="modal-data"></span></p>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn-fechar">Fechar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            const modal = document.getElementById("modalPerfil");
            const btns = document.querySelectorAll(".btn-ver-perfil");
            const closeBtns = document.querySelectorAll(".close-btn, .btn-fechar");

            const mId = document.getElementById("modal-id");
            const mEmail = document.getElementById("modal-email");
            const mVbucks = document.getElementById("modal-vbucks");
            const mItem = document.getElementById("modal-item");
            const mData = document.getElementById("modal-data");

            btns.forEach(btn => {
                btn.addEventListener("click", () => {
                    mId.innerText = "#" + btn.dataset.id;
                    mEmail.innerText = btn.dataset.email;
                    mVbucks.innerText = btn.dataset.vbucks;
                    mItem.innerText = btn.dataset.ultimaCompra;
                    mData.innerText = btn.dataset.dataCompra;
                    modal.classList.add("show");
                });
            });

            const fecharModal = () => modal.classList.remove("show");
            closeBtns.forEach(btn => btn.addEventListener("click", fecharModal));
            window.addEventListener("click", (e) => {
                if (e.target === modal) fecharModal();
            });

            const btnPrev = document.getElementById("prev");
            const btnNext = document.getElementById("next");
            const currentPage = <?= $page ?>;

            if(btnPrev && !btnPrev.disabled) {
                btnPrev.addEventListener("click", () => {
                    window.location.href = "?page=" + (currentPage - 1);
                });
            }

            if(btnNext && !btnNext.disabled) {
                btnNext.addEventListener("click", () => {
                    window.location.href = "?page=" + (currentPage + 1);
                });
            }
        });
    </script>
</body>
</html>