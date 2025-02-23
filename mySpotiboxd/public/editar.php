<?php
session_start();
require '../backend/db.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Obtém o ID da avaliação a ser editada
$avaliacao_id = $_GET['id'] ?? null;
if (!$avaliacao_id) {
    header("Location: index.php");
    exit;
}

// Puxa a avaliação específica do usuário logado para edição
$stmt = $pdo->prepare("
    SELECT a.nota, a.resenha, a.musica_id, a.usuario_id, m.titulo, m.artista 
    FROM avaliacoes a 
    JOIN musicas m ON a.musica_id = m.id 
    WHERE a.id = ? AND a.usuario_id = ?
");
$stmt->execute([$avaliacao_id, $_SESSION['usuario_id']]);
$avaliacao = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se a avaliação foi encontrada
if (!$avaliacao) {
    header("Location: index.php");
    exit;
}

// Puxa todas as músicas para exibição no dropdown
$stmt = $pdo->query("SELECT id, titulo, artista FROM musicas");
$musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Atualiza a avaliação no banco de dados se o formulário for enviado via método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $musica_id = $_POST['musica_id'];
    $nota = $_POST['nota'];
    $resenha = $_POST['resenha'];

    // Prepara a consulta para atualizar a avaliação
    $stmt = $pdo->prepare("UPDATE avaliacoes SET musica_id = ?, nota = ?, resenha = ? WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$musica_id, $nota, $resenha, $avaliacao_id, $_SESSION['usuario_id']]);

    // Redireciona para a página inicial após a atualização bem-sucedida
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avaliação - mySpotiboxd</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo">mySpotiboxd</div>
        <div class="user-options">
            <a href="index.php">Voltar</a> | <a href="../backend/logout.php">Sair</a>
        </div>
    </header>

    <main>
        <section class="form-container">
            <h1>Editar Avaliação</h1>
            <form method="POST">
                <label for="musica_id">Música:</label>
                <select name="musica_id" id="musica_id" required>
                    <?php foreach ($musicas as $musica): ?>
                        <option value="<?php echo $musica['id']; ?>" <?php if ($musica['id'] == $avaliacao['musica_id']) echo 'selected'; ?>>
                            <?php echo $musica['titulo'] . " - " . $musica['artista']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="nota">Nota (0-5):</label>
                <input type="number" name="nota" id="nota" min="0" max="5" value="<?php echo $avaliacao['nota']; ?>" required>

                <label for="resenha">Resenha (opcional):</label>
                <textarea name="resenha" id="resenha" rows="4"><?php echo $avaliacao['resenha']; ?></textarea>

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>

</html>
