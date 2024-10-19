<?php
// Incluir a conexão com o banco de dados usando PDO
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Recebe a ação a ser realizada
$action = $_REQUEST['action'];
$response = [];

switch ($action) {
    case 'cadastrar':
        // Recebe o ID do poste e outros dados do formulário
        $id_poste = filter_input(INPUT_POST, 'id_poste', FILTER_VALIDATE_INT);
        $codigo = isset($_REQUEST['codigo']) ? $_REQUEST['codigo'] : null;
        $tipo = isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : null;
        $parafuso_nivel_1 = isset($_REQUEST['parafuso_nivel_1']) ? $_REQUEST['parafuso_nivel_1'] : null;
        $parafuso_nivel_2 = isset($_REQUEST['parafuso_nivel_2']) ? $_REQUEST['parafuso_nivel_2'] : null;
    
        // Verifica se o código do poste foi preenchido
        if (empty($codigo)) {
            $response['status'] = 'error';
            $response['message'] = 'O código do poste é obrigatório.';
            exit;
        }
    
        // Se tiver ID do poste, atualiza o registro existente
        if ($id_poste) {
            $sql = "UPDATE postes SET codigo = :codigo, tipo = :tipo, parafuso_nivel_1 = :parafuso_nivel_1, parafuso_nivel_2 = :parafuso_nivel_2 WHERE id_poste = :id_poste";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':parafuso_nivel_1', $parafuso_nivel_1);
            $stmt->bindParam(':parafuso_nivel_2', $parafuso_nivel_2);
            $stmt->bindParam(':id_poste', $id_poste);
    
            try {
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Poste atualizado com sucesso!';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = "Erro ao atualizar poste.";
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = "Erro ao atualizar poste: " . $e->getMessage();
            }
        } else {
            // Se não tiver ID, insere um novo poste
            $sql = "INSERT INTO postes (codigo, tipo, parafuso_nivel_1, parafuso_nivel_2) VALUES (:codigo, :tipo, :parafuso_nivel_1, :parafuso_nivel_2)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':parafuso_nivel_1', $parafuso_nivel_1);
            $stmt->bindParam(':parafuso_nivel_2', $parafuso_nivel_2);
    
            try {
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Poste cadastrado com sucesso!';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = "Erro ao cadastrar poste.";
                }
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = "Erro ao cadastrar poste: " . $e->getMessage();
            }
        }
    
    break;
    

    case 'listar':
        try {
            $sql = "SELECT * 
                    FROM postes";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $postes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($postes) {
                $response['status'] = 'success';
                $response['data'] = $postes;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Nenhumm material encontrada.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao listar postes: " . $e->getMessage();
        }
        break;

    case 'editar':
        // Recebe o id_estrutura da requisição (pode ser via GET ou POST)
        $id_poste = $_REQUEST['id'];

        if (!$id_poste) {
            $response['status'] = 'error';
            $response['message'] = 'ID inválido. Verifique os campos e tente novamente.';
            exit;
        }

        try {
            // Busca a estrutura no banco de dados
            $sql_estrutura = "SELECT * FROM postes WHERE id = :id_poste";
            $stmt = $pdo->prepare($sql_estrutura);
            $stmt->execute(['id_poste' => $id_poste]);
            $postes = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$postes) {
                $response['status'] = 'error';
                $response['message'] = 'Poste não encontrado.';
                exit;
            }

            // Retorna os dados para serem preenchidos no formulário de edição
            $response['status'] = 'success';
            $response['postes'] = $postes; // Dados dos materiais associados
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao carregar dados do poste: " . $e->getMessage();
            echo json_encode($response);
        }
        break;

    case 'excluir':
        $id_poste = $_REQUEST['id'];
        if (!$id_poste) {
            $response['status'] = 'error';
            $response['message'] = 'ID do poste inválido.';
            echo json_encode($response);
            exit;
        }

        try {
            // Remover a estrutura
            $sql = "DELETE FROM postes WHERE id = :id_poste";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_poste' => $id_poste]);

            $response['status'] = 'success';
            $response['message'] = 'Poste excluído com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao excluir poste: " . $e->getMessage();
        }
        break;

    default:
        $response['status'] = 'error';
        $response['message'] = 'Ação inválida.';
        break;
}

echo json_encode($response);
