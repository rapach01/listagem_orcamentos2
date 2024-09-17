<?php
// Inclui o arquivo de conexão
include '../enviroments/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $parafuso_nivel_1 = $_POST['parafuso_nivel_1'];
    $parafuso_nivel_2 = $_POST['parafuso_nivel_2'];

    // Validações simples (pode adicionar mais validações conforme necessário)
    if (empty($codigo) || empty($tipo) || empty($parafuso_nivel_1) || empty($parafuso_nivel_2)) {
        echo "Todos os campos são obrigatórios!";
        exit();
    }

    try {
        // Insere os dados no banco de dados
        $sql = "INSERT INTO postes (codigo, tipo, parafuso_nivel_1, parafuso_nivel_2) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$codigo, $tipo, $parafuso_nivel_1, $parafuso_nivel_2]);

        echo "Poste cadastrado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar o poste: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido!";
}
?>
