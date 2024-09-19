<?php
include '../environments/database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recebe a ação a ser realizada
$action =$_REQUEST['action'];
$response = [];

switch ($action) {
    case 'cadastrar':
        // Recebe dados do formulário e valida
        $nome_estrutura = filter_input(INPUT_POST, 'nome_estrutura', FILTER_SANITIZE_STRING);
        $materiais = filter_input(INPUT_POST, 'material', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $quantidades = filter_input(INPUT_POST, 'quantidade', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (!$nome_estrutura || empty($materiais) || empty($quantidades)) {
            $response['status'] = 'error';
            $response['message'] = 'Dados inválidos. Verifique os campos e tente novamente.';
            echo json_encode($response);
            exit;
        }

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

            $response['status'] = 'success';
            $response['message'] = 'Estrutura cadastrada com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao cadastrar estrutura: " . $e->getMessage();
        }
    break;

    case 'listar':
        try {
            $sql = "SELECT e.id_estrutura, e.descricao_estrutura, em.id_material, em.quantidade 
                    FROM estruturas e 
                    LEFT JOIN estrutura_materiais em ON e.id_estrutura = em.id_estrutura";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            $estruturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($estruturas) {
                $response['status'] = 'success';
                $response['data'] = $estruturas;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Nenhuma estrutura encontrada.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao listar estruturas: " . $e->getMessage();
        }
        break;

    case 'editar':
        // Recebe dados do formulário e valida
        $id_estrutura = filter_input(INPUT_POST, 'id_estrutura', FILTER_VALIDATE_INT);
        $nome_estrutura = filter_input(INPUT_POST, 'nome_estrutura', FILTER_SANITIZE_STRING);
        $materiais = filter_input(INPUT_POST, 'material', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $quantidades = filter_input(INPUT_POST, 'quantidade', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (!$id_estrutura || !$nome_estrutura || empty($materiais) || empty($quantidades)) {
            $response['status'] = 'error';
            $response['message'] = 'Dados inválidos. Verifique os campos e tente novamente.';
            echo json_encode($response);
            exit;
        }

        try {
            // Atualizar a estrutura no banco de dados
            $sql_estrutura = "UPDATE estruturas SET descricao_estrutura = :nome_estrutura WHERE id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql_estrutura);
            $stmt->execute(['nome_estrutura' => $nome_estrutura, 'id_estrutura' => $id_estrutura]);

            // Atualizar os materiais associados
            // Primeiro, removemos os materiais existentes
            $sql_delete_material = "DELETE FROM estrutura_materiais WHERE id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql_delete_material);
            $stmt->execute(['id_estrutura' => $id_estrutura]);

            // Inserir os novos materiais associados
            for ($i = 0; $i < count($materiais); $i++) {
                $id_material = $materiais[$i];
                $quantidade = $quantidades[$i];

                $sql_material = "INSERT INTO estrutura_materiais (id_estrutura, id_material, quantidade) 
                                 VALUES (:id_estrutura, :id_material, :quantidade)";
                $stmt = $pdo->prepare($sql_material);
                $stmt->execute(['id_estrutura' => $id_estrutura, 'id_material' => $id_material, 'quantidade' => $quantidade]);
            }

            $response['status'] = 'success';
            $response['message'] = 'Estrutura atualizada com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao atualizar estrutura: " . $e->getMessage();
        }
        break;

    case 'excluir':
        $id_estrutura = filter_input(INPUT_POST, 'id_estrutura', FILTER_VALIDATE_INT);
        if (!$id_estrutura) {
            $response['status'] = 'error';
            $response['message'] = 'ID da estrutura inválido.';
            echo json_encode($response);
            exit;
        }

        try {
            // Remover os materiais associados
            $sql_delete_material = "DELETE FROM estrutura_materiais WHERE id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql_delete_material);
            $stmt->execute(['id_estrutura' => $id_estrutura]);

            // Remover a estrutura
            $sql = "DELETE FROM estruturas WHERE id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_estrutura' => $id_estrutura]);

            $response['status'] = 'success';
            $response['message'] = 'Estrutura excluída com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao excluir estrutura: " . $e->getMessage();
        }
        break;

    default:
        $response['status'] = 'error';
        $response['message'] = 'Ação inválida.';
        break;
}

echo json_encode($response);
