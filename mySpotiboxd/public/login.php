<?php
session_start(); // Inicia a sessão
require '../backend/db.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Obtém o email do formulário
    $senha = $_POST['senha']; // Obtém a senha do formulário

    // Prepara a consulta para buscar o usuário com o email e senha fornecidos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]); // Executa a consulta
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém o resultado da consulta

    // Verifica se o usuário foi encontrado
    if ($usuario) {
        // Armazena os dados do usuário na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        header("Location: index.php"); // Redireciona para a página inicial
        exit; // Encerra o script
    } else {
        $erro = "Email ou senha incorretos!"; // Define a mensagem de erro
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - mySpotiboxd</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">mySpotiboxd</div>
    </header>

    <main>
        <section class="form-container">
            <h1>Login</h1>
            <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>

                <button type="submit">Entrar</button>
            </form>
            <div class="user-options">
                <a href="cadastro.php">Criar conta</a>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>
</html>