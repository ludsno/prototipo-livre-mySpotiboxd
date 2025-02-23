<?php
require '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; // Obtém o nome do formulário
    $email = $_POST['email']; // Obtém o email do formulário
    $senha = $_POST['senha']; // Obtém a senha do formulário

    // Verifica se o email já existe no banco de dados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $erro = "Esse email já está cadastrado!"; // Define a mensagem de erro se o email já estiver cadastrado
    } else {
        // Insere o novo usuário no banco de dados
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);
        header("Location: login.php"); // Redireciona para a página de login após o cadastro
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - mySpotiboxd</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo">mySpotiboxd</div>
        <div class="user-options">
            <a href="login.php">Já tenho conta</a>
        </div>
    </header>

    <main>
        <section class="form-container">
            <h1>Criar Conta</h1>
            <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
            <form method="POST">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>

</html>