<?php
require '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']); // Remove espaços extras
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; // Não usamos trim pra senha, pra respeitar espaços

    // Validações
    $erros = [];

    // Nome: 2-50 caracteres, só letras e espaços
    if (empty($nome) || strlen($nome) < 2 || strlen($nome) > 50 || !preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nome)) {
        $erros[] = "O nome deve ter entre 2 e 50 caracteres e conter apenas letras e espaços.";
    }

    // Email: formato válido
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Por favor, insira um email válido.";
    }

    // Senha: mínimo 6 caracteres
    if (empty($senha) || strlen($senha) < 6) {
        $erros[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    // Se não houver erros, prossegue
    if (empty($erros)) {
        // Verifica se o email já existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $erros[] = "Esse email já está cadastrado!";
        } else {
            // Criptografa e salva
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash]);
            header("Location: login.php");
            exit;
        }
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
            <?php if (!empty($erros)): ?>
                <ul style="color: red;">
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form method="POST">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2025 MySpotiboxd - Feito por alunos de CC</p>
    </footer>
</body>
</html>
