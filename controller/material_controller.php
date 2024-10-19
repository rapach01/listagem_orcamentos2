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
        // Recebe o ID do material e o nome do material do formulário
        $id_material = filter_input(INPUT_POST, 'id_material', FILTER_VALIDATE_INT);
        $nome_material = trim($_POST['nome_material']); // Remove espaços em branco no início e no fim
    
        // Verifica se o nome do material foi preenchido
        if (empty($nome_material)) {
            $response['status'] = 'error';
            $response['message'] = 'O nome do material é obrigatório.';
            exit;
        }
    
        // Se tiver ID do material, atualiza o registro existente
        if ($id_material) {
            $sql = "UPDATE materiais SET nome_material = :nome_material WHERE id_material = :id_material";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_material', $nome_material);
            $stmt->bindParam(':id_material', $id_material);
    
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = $id_material ? 'Material atualizado com sucesso!' : 'Material cadastrado com sucesso!';
            } else {
                $response['status'] = 'error';
                $response['message'] = "Erro ao atualizar material: " . $e->getMessage();
            }
        } else {
            // Se não tiver ID, insere um novo material
            $sql = "INSERT INTO materiais (nome_material) VALUES (:nome_material)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_material', $nome_material);
    
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = $id_material ? 'Material atualizado com sucesso!' : 'Material cadastrado com sucesso!';
            } else {
                $response['status'] = 'error';
                $response['message'] = "Erro ao cadastrar material: " . $e->getMessage();
            }
        }   

    break;

    case 'listar':
        try {
            $sql = "SELECT * 
                    FROM materiais";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($materiais) {
                $response['status'] = 'success';
                $response['data'] = $materiais;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Nenhumm material encontrada.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao listar materiais: " . $e->getMessage();
        }
        break;

    case 'editar':
        // Recebe o id_estrutura da requisição (pode ser via GET ou POST)
        $id_material = $_REQUEST['id'];

        if (!$id_material) {
            $response['status'] = 'error';
            $response['message'] = 'ID inválido. Verifique os campos e tente novamente.';
            exit;
        }

        try {
            // Busca a estrutura no banco de dados
            $sql_estrutura = "SELECT * FROM materiais WHERE id_material = :id_material";
            $stmt = $pdo->prepare($sql_estrutura);
            $stmt->execute(['id_material' => $id_material]);
            $materiais = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$materiais) {
                $response['status'] = 'error';
                $response['message'] = 'Material não encontrado.';
                exit;
            }

            // Retorna os dados para serem preenchidos no formulário de edição
            $response['status'] = 'success';
            $response['materiais'] = $materiais; // Dados dos materiais associados
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao carregar dados da estrutura: " . $e->getMessage();
            echo json_encode($response);
        }
        break;

    case 'excluir':
        $id_material = $_REQUEST['id'];
        if (!$id_material) {
            $response['status'] = 'error';
            $response['message'] = 'ID do material inválido.';
            echo json_encode($response);
            exit;
        }

        try {
            // Remover a estrutura
            $sql = "DELETE FROM materiais WHERE id_material = :id_material";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id_material' => $id_material]);

            $response['status'] = 'success';
            $response['message'] = 'Material excluído com sucesso!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Erro ao excluir material: " . $e->getMessage();
        }
        break;

    default:
        $response['status'] = 'error';
        $response['message'] = 'Ação inválida.';
        break;
}

echo json_encode($response);