<?php
session_start();
include 'conecta.php';

if ($_POST) {
  $email = $conn->real_escape_string($_POST['email']);
  $senha = $_POST['senha'];

  $sql = "SELECT * FROM usuarios WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($senha, $user['senha'])) {
      $_SESSION['id_sistema'] = 'sislogin2024*';
      $_SESSION['user_id'] = $user['ID'];
      $_SESSION['vbucks'] = $user['vbucks'];
      header("Location: ../catalogo.php");
      exit;
    }
  }
  header("Location: ../login.php?erro=1"); // Volta com erro
}
?>