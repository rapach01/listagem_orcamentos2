<?php
// Incluir a conexão com o banco de dados usando PDO
include '../enviroments/database.php';

// Receber dados do formulário
$nome_material = $_POST['nome_material'];

// Inserir material no banco de dados com prepared statements
$sql = "INSERT INTO materiais (nome_material) VALUES (:nome_material)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['nome_material' => $nome_material]);
    echo "Material cadastrado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao cadastrar material: " . $e->getMessage();
}
