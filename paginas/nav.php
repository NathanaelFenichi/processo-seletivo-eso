<?php
  if (str_contains($_SERVER['REQUEST_URI'], 'index') || (substr($_SERVER['REQUEST_URI'], -1) == '/')) {
  ?>
  <nav id="nav-geral">
    <div class="qtd-V-bucks">
      <img src="img/icons/vbuck.png" alt="V-bucks">
      <?php session_start(); 
      if(isset($_SESSION['vbucks'])) {
          echo "<h2>" . $_SESSION['vbucks'] . "</h2>";
      } else {
          echo "<h2>0</h2>";
      }
      ?>
    </div>
    <ul class="nav-paginas">
      <li><a href="index.php" class="active">Home</a></li>
      <li><a href="paginas/paginas.php">Loja</a></li>
      <li><a href="paginas/perfil.php">Perfil</a></li>
    </ul>

    <?php
    if(isset($_SESSION['id_sistema']) && $_SESSION['id_sistema'] == 'sislogin2024*'){
        echo '<button><a href="paginas/logout.php">Sair</a></button>';
    } else {
        echo '<button><a href="paginas/login.php">Entrar</a></button>';
    }
    ?>
  </nav>
<?php
  }else {
  ?>
<nav id="nav-login">
    <div class="qtd-V-bucks">
      <img src="../img/icons/vbuck.png" alt="V-bucks">
      <?php session_start(); 
      if(isset($_SESSION['vbucks'])) {
          echo "<h2>" . $_SESSION['vbucks'] . "</h2>";
      } else {
          echo "<h2>0</h2>";
      }
      ?>
    </div>
    <ul class="nav-paginas">
      <li><a href="../index.php" class="active">Home</a></li>
      <li><a href="paginas/paginas.php">Loja</a></li>
      <li><a href="paginas/perfil.php">Perfil</a></li>
    </ul>

    <?php
    if(isset($_SESSION['id_sistema']) && $_SESSION['id_sistema'] == 'sislogin2024*'){
        echo '<button><a href="paginas/logout.php">Sair</a></button>';
    } else {
        echo '<button><a href="paginas/login.php">Entrar</a></button>';
    }
    ?>
  </nav>
<?php
  }
  ?>