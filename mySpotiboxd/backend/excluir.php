<?php
session_start(); // Inicia a sessão
require 'db.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/login.php"); // Redireciona para a página de login se não estiver logado
    exit; // Encerra o script
}

// Obtém o ID da avaliação a ser excluída a partir da URL
$avaliacao_id = $_GET['id'] ?? null;
if (!$avaliacao_id) {
    header("Location: ../public/index.php"); // Redireciona para a página inicial se o ID não for fornecido
    exit; // Encerra o script
}

// Prepara a declaração SQL para deletar a avaliação do usuário logado
$stmt = $pdo->prepare("DELETE FROM avaliacoes WHERE id = ? AND usuario_id = ?");
$stmt->execute([$avaliacao_id, $_SESSION['usuario_id']]); // Executa a declaração com os parâmetros fornecidos

header("Location: ../public/index.php"); // Redireciona para a página inicial após a exclusão
exit;
?>