<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/login.php");
    exit;
}

$avaliacao_id = $_GET['id'] ?? null;
if (!$avaliacao_id) {
    header("Location: ..public/index.php");
    exit;
}

// Deleta a avaliação (só do usuário logado)
$stmt = $pdo->prepare("DELETE FROM avaliacoes WHERE id = ? AND usuario_id = ?");
$stmt->execute([$avaliacao_id, $_SESSION['usuario_id']]);

header("Location: ../public/index.php");
exit;
?>