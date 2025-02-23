<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
require '../backend/db.php';

// Verifica se o usuário está logado, caso contrário redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Puxa as avaliações do banco de dados
$stmt = $pdo->query("
    SELECT a.id, a.nota, a.resenha, a.data, a.usuario_id, u.nome, m.titulo, m.artista 
    FROM avaliacoes a 
    JOIN usuarios u ON a.usuario_id = u.id 
    JOIN musicas m ON a.musica_id = m.id 
    ORDER BY a.data DESC
");

// Armazena as avaliações em um array
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mySpotiboxd - Home</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="icon" href="favicon.ico" type="image/x-icon"> -->
    <meta name="description" content="mySpotiboxd - Sua plataforma para avaliar músicas e álbuns.">
    <meta name="keywords" content="música, álbuns, avaliações, resenhas, mySpotiboxd, artistas, playlists, críticas, reviews, notas, comentários, música online">
    <meta name="author" content="HKLRW">
</head>

<body>
    <header>
        <div class="logo">mySpotiboxd</div>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar músicas, álbuns ou artistas...">
            <button>Pesquisar</button>
        </div>
        <div class="user-options">
            Bem-vindo, <?php echo $_SESSION['nome']; ?> |
            <a href="perfil.php">Meu Perfil</a> |
            <a href="adicionar.php">Adicionar</a> |
            <a href="../backend/logout.php">Sair</a>
        </div>
    </header>

    <main>
        <section class="feed">
            <h1>Atividades recentes</h1>
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="post">
                    <img src="https://placehold.co/50" alt="Capa do álbum">
                    <div class="post-content">
                        <!-- Exibe o nome do usuário, a nota dada, o título da música e o artista -->
                        <p><strong><?php echo $avaliacao['nome']; ?></strong> deu <?php echo $avaliacao['nota']; ?> ★ para "<?php echo $avaliacao['titulo']; ?>" - <?php echo $avaliacao['artista']; ?></p>
                        <!-- Exibe a resenha do usuário -->
                        <p class="review">"<?php echo $avaliacao['resenha']; ?>"</p>
                        <!-- Exibe a data e hora da avaliação formatada -->
                        <span class="timestamp"><?php echo date('d/m/Y H:i', strtotime($avaliacao['data'])); ?></span>
                        <!-- Exibe os botões de editar e excluir apenas se o usuário logado for o autor da avaliação -->
                        <?php if ($avaliacao['usuario_id'] == $_SESSION['usuario_id']): ?>
                            <a href="editar.php?id=<?php echo $avaliacao['id']; ?>" class="edit-btn">Editar</a>
                            <a href="../backend/excluir.php?id=<?php echo $avaliacao['id']; ?>" class="delete-btn" onclick="return confirm('Tem certeza que quer excluir esta avaliação?');">Excluir</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <footer>
        <p>© 2025 mySpotiboxd - Feito por HKLRW</p>
    </footer>
</body>

</html>