<?php
session_start(); // Inicia a sessão
require '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos!";
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