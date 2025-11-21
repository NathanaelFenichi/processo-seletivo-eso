<?php
session_start();
require '../conecta.php';

// Busca todos os usuários
$sql = "SELECT ID, nome, vbucks FROM usuarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Comunidade - Fortnite</title>
  <link rel="stylesheet" href="../css/geral.css">
  <style>
      .lista-users { display: grid; gap: 15px; max-width: 800px; margin: 40px auto; }
      .card-user { background: white; padding: 20px; border-radius: 10px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: inherit; transition: 0.2s; }
      .card-user:hover { transform: translateY(-3px); border-left: 5px solid #33CC99; }
      .user-avatar { width: 50px; height: 50px; background: #33CC99; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px; margin-right: 15px; }
      .user-info h3 { margin: 0; font-size: 18px; }
      .user-stats { color: #777; font-size: 14px; }
  </style>
</head>
<body>

  <?php include 'nav.php'; ?>

  <main class="container">
      <h1 style="text-align:center; margin-top:30px;">Comunidade Fortnite</h1>
      
      <div class="lista-users">
          <?php while($user = $result->fetch_assoc()): ?>
            <a href="perfil_publico.php?id=<?php echo $user['ID']; ?>" class="card-user">
                <div style="display:flex; align-items:center;">
                    <div class="user-avatar"><?php echo strtoupper(substr($user['nome'], 0, 1)); ?></div>
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['nome']); ?></h3>
                        <span class="user-stats">Ver inventário ➔</span>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span style="font-weight:bold; color:#00bcd4;"><?php echo $user['vbucks']; ?> V-Bucks</span>
                </div>
            </a>
          <?php endwhile; ?>
      </div>
  </main>

</body>
</html>