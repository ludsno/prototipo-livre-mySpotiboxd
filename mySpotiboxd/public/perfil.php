<?php
session_start();
require '../backend/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Puxa as avaliações do usuário logado
$stmt = $pdo->prepare("
    SELECT a.id, a.nota, a.resenha, a.data, m.titulo, m.artista 
    FROM avaliacoes a 
    JOIN musicas m ON a.musica_id = m.id 
    WHERE a.usuario_id = ? 
    ORDER BY a.data DESC
");
$stmt->execute([$_SESSION['usuario_id']]);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - mySpotiboxd</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">mySpotiboxd</div>
        <div class="user-options">
            <a href="index.php">Home</a> | 
            <a href="adicionar.php">Adicionar</a> | 
            <a href="../backend/logout.php">Sair</a>
        </div>
    </header>

    <main>
        <section class="feed">
            <h1>Meu Perfil - <?php echo $_SESSION['nome']; ?></h1>
            <?php if (empty($avaliacoes)): ?>
                <p>Você ainda não fez nenhuma avaliação. <a href="adicionar.php">Adicione uma!</a></p>
            <?php else: ?>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                    <div class="post">
                        <img src="https://placehold.co/50" alt="Capa do álbum">
                        <div class="post-content">
                            <p><strong><?php echo $_SESSION['nome']; ?></strong> deu <?php echo $avaliacao['nota']; ?> ★ para "<?php echo $avaliacao['titulo']; ?>" - <?php echo $avaliacao['artista']; ?></p>
                            <p class="review">"<?php echo $avaliacao['resenha']; ?>"</p>
                            <span class="timestamp"><?php echo date('d/m/Y H:i', strtotime($avaliacao['data'])); ?></span>
                            <a href="editar.php?id=<?php echo $avaliacao['id']; ?>" class="edit-btn">Editar</a>
                            <a href="../backend/excluir.php?id=<?php echo $avaliacao['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que quer excluir esta avaliação?');">Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>
</html>