<?php
session_start();

session_destroy();
session_unset();

header('Location: ../paginas/login.php');
?>