<?php
$host = 'localhost'; // Endereço do servidor MySQL
$db   = 'setup'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$pass = ''; // Senha do banco de dados
$charset = 'utf8mb4'; // Charset recomendado

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções para a conexão com o banco de dados
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o fetch padrão para arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false, // Desativa a emulação de prepares
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit(); // Encerra o script
}
