<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


// Recebe a ação a ser realizada
$action = $_REQUEST['action'];
$response = [];

switch ($action) {
    case 'cadastrar':
        // Recebe dados do formulário e valida
        $id_estrutura = filter_input(INPUT_POST, 'id_estrutura', FILTER_VALIDATE_INT);
        $nome_estrutura = filter_input(INPUT_POST, 'nome_estrutura', FILTER_SANITIZE_STRING);
        $materiais = filter_input(INPUT_POST, 'material', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $quantidades = filter_input(INPUT_POST, 'quantidade', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (!$nome_estrutura || empty($materiais) || empty($quantidades)) {
            $response['status'] = 'error';
            $response['message'] = 'Dados inválidos. Verifique os campos e tente novamente.';
            exit;
        }

        try {
            if ($id_estrutura) {
                // Atualizar a estrutura existente
                $sql_estrutura = "UPDATE estruturas SET descricao_estrutura = :nome_estrutura WHERE id_estrutura = :id_estrutura";
                $stmt = $pdo->prepare($sql_estrutura);
                $stmt->execute(['nome_estrutura' => $nome_estrutura, 'id_estrutura' => $id_estrutura]);

                // Deletar materiais antigos associados a essa estrutura
                $sql_delete_materiais = "DELETE FROM estrutura_materiais WHERE id_estrutura = :id_estrutura";
                $stmt = $pdo->prepare($sql_delete_materiais);
                $stmt->execute(['id_estrutura' => $id_estrutura]);
            } else {
                // Inserir uma nova estrutura
                $sql_estrutura = "INSERT INTO estruturas (descricao_estrutura) VALUES (:nome_estrutura)";
                $stmt = $pdo->prepare($sql_estrutura);
                $stmt->execute(['nome_estrutura' => $nome_estrutura]);

                // Pegar o ID da nova estrutura inserida
                $id_estrutura = $pdo->lastInsertId();
            }

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
            $response['message'] = $id_estrutura ? 'Estrutura atualizada com sucesso!' : 'Estrutura cadastrada com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao cadastrar/atualizar estrutura: " . $e->getMessage();
        }

    break;

    case 'listar':
        try {
            $sql = "SELECT * FROM estruturas e WHERE 1";
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
        // Recebe o id_estrutura da requisição (pode ser via GET ou POST)
        $id_estrutura = $_REQUEST['id'];

        if (!$id_estrutura) {
            $response['status'] = 'error';
            $response['message'] = 'ID inválido. Verifique os campos e tente novamente.';
            echo json_encode($response);
            exit;
        }

        try {
            // Busca a estrutura no banco de dados
            $sql_estrutura = "SELECT * FROM estruturas WHERE id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql_estrutura);
            $stmt->execute(['id_estrutura' => $id_estrutura]);
            $estrutura = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$estrutura) {
                $response['status'] = 'error';
                $response['message'] = 'Estrutura não encontrada.';

                exit;
            }

            // Busca os materiais associados a esta estrutura
            $sql_materiais = "SELECT em.id_material, em.quantidade, m.nome_material 
                              FROM estrutura_materiais em 
                              JOIN materiais m ON em.id_material = m.id_material
                              WHERE em.id_estrutura = :id_estrutura";
            $stmt = $pdo->prepare($sql_materiais);
            $stmt->execute(['id_estrutura' => $id_estrutura]);
            $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retorna os dados para serem preenchidos no formulário de edição
            $response['status'] = 'success';
            $response['estrutura'] = $estrutura; // Dados da estrutura
            $response['materiais'] = $materiais; // Dados dos materiais associados
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao carregar dados da estrutura: " . $e->getMessage();
            echo json_encode($response);
        }
        break;

    case 'excluir':
        $id_estrutura = $_REQUEST['id'];
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
