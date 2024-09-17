<?php
include '../enviroments/database.php';

// Recebe dados do formulário
$nome_estrutura = $_POST['nome_estrutura'];
$materiais = $_POST['material'];
$quantidades = $_POST['quantidade'];

try {
    // Inserir a estrutura no banco de dados
    $sql_estrutura = "INSERT INTO estruturas (descricao_estrutura) VALUES (:nome_estrutura)";
    $stmt = $pdo->prepare($sql_estrutura);
    $stmt->execute(['nome_estrutura' => $nome_estrutura]);

    // Pegar o ID da estrutura inserida
    $id_estrutura = $pdo->lastInsertId();

    // Inserir os materiais associados
    for ($i = 0; $i < count($materiais); $i++) {
        $id_material = $materiais[$i];
        $quantidade = $quantidades[$i];
        
        $sql_material = "INSERT INTO estrutura_materiais (id_estrutura, id_material, quantidade) 
                         VALUES (:id_estrutura, :id_material, :quantidade)";
        $stmt = $pdo->prepare($sql_material);
        $stmt->execute(['id_estrutura' => $id_estrutura, 'id_material' => $id_material, 'quantidade' => $quantidade]);
    }

    echo "Estrutura cadastrada com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao cadastrar estrutura: " . $e->getMessage();
}
?>
