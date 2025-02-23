<?php
// Inicia a sessão
session_start();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: ../public/login.php");
exit;
?>