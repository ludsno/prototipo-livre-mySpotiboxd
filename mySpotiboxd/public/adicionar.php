<?php
require '../backend/db.php'; // Conexão com o banco

// Se o formulário foi enviado, salva a avaliação
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $usuario_id = 1; // Fixo por agora (João, por exemplo). Depois mudamos com login
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit;
    }
    $usuario_id = $_SESSION['usuario_id'];
    $musica_id = $_POST['musica_id'];
    $nota = $_POST['nota'];
    $resenha = $_POST['resenha'];

    $stmt = $pdo->prepare("INSERT INTO avaliacoes (usuario_id, musica_id, nota, resenha) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $musica_id, $nota, $resenha]);

    header("Location: index.php"); // Volta pra home após salvar
    exit;
}

// Puxa as músicas disponíveis pra escolher
$stmt = $pdo->query("SELECT id, titulo, artista FROM musicas");
$musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Avaliação - mySpotiboxd</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo">mySpotiboxd</div>
        <div class="user-options">
            <a href="index.php">Voltar</a> | <a href="#">Login</a>
        </div>
    </header>

    <main>
        <section class="form-container">
            <h1>Adicionar uma Avaliação</h1>
            <form method="POST">
                <label for="musica_id">Música:</label>
                <select name="musica_id" id="musica_id" required>
                    <?php foreach ($musicas as $musica): ?>
                        <option value="<?php echo $musica['id']; ?>">
                            <?php echo $musica['titulo'] . " - " . $musica['artista']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="nota">Nota (0-5):</label>
                <input type="number" name="nota" id="nota" min="0" max="5" required>

                <label for="resenha">Resenha (opcional):</label>
                <textarea name="resenha" id="resenha" rows="4"></textarea>

                <button type="submit">Salvar Avaliação</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>

</html>