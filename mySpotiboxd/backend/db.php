<?php
// Configurações do banco de dados
$host = 'localhost'; // Endereço do servidor MySQL
$db = 'musicbox'; // Nome do banco de dados
$user = 'root'; // Usuário padrão do XAMPP
$pass = ''; // Senha padrão do XAMPP é vazia

try {
    // Cria uma nova instância de PDO para conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Exibe uma mensagem de erro se a conexão falhar
    echo "Erro na conexão: " . $e->getMessage();
}
?>