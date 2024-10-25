<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$response = array();
if (!empty($action)) {
    switch ($action) {
        case 'buscarPostes':
            $id = $_REQUEST['id'];
            $sql = "SELECT parafuso_nivel_1, parafuso_nivel_2 FROM postes WHERE id_poste = $id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $postes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response['postes'] = $postes;
        break;
        
        case 'listar':
            foreach ($_POST['postes'] as $i => $poste) {
                $estrutura1 = $_POST['estruturas1'][$i];
                $parafuso_nivel_1 = $_POST['parafuso_nivel_1'][$i];
                $estrutura2 = $_POST['estruturas2'][$i];
                $parafuso_nivel_2 = $_POST['parafuso_nivel_2'][$i];
            
                // Consulta para obter o tipo do poste
                $sqlPoste = "SELECT tipo FROM postes WHERE id_poste = ?";
                $stmtPoste = $pdo->prepare($sqlPoste);
                $stmtPoste->execute([$poste]);
                $tipoPoste = $stmtPoste->fetchColumn();
            
                // Consulta para obter os materiais para estrutura1
                $sql1 = "SELECT m.id_material, m.nome_material, em.quantidade 
                         FROM estrutura_materiais em 
                         JOIN materiais m ON em.id_material = m.id_material 
                         WHERE em.id_estrutura = ?";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->execute([$estrutura1]);
                $materiais1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            
                // Consulta para obter os materiais para estrutura2
                $sql2 = "SELECT m.id_material, m.nome_material, em.quantidade 
                         FROM estrutura_materiais em 
                         JOIN materiais m ON em.id_material = m.id_material 
                         WHERE em.id_estrutura = ?";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([$estrutura2]);
                $materiais2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
                // Adiciona os dados ao array de resposta
                $response['data'][] = [
                    'linha' => $i + 1,
                    'poste' => $poste,
                    'tipo_poste' => $tipoPoste, // Adiciona o tipo do poste
                    'estrutura1' => $estrutura1,
                    'parafuso_nivel_1' => $parafuso_nivel_1,
                    'estrutura2' => $estrutura2,
                    'parafuso_nivel_2' => $parafuso_nivel_2,
                    'materiais_estrutura1' => $materiais1,
                    'materiais_estrutura2' => $materiais2
                ];
            }
            
        break;

        case 'xlsx':
            $sheet = $spreadsheet->getActiveSheet();
            $materiais = isset($_REQUEST['materiais']) ? $_REQUEST['materiais'] : null;

            /*  */
        break;

        case 'pdf':
        break;

        default:
            $response['error'] = 'Ação não encontrada';
        break;
    }
}

if (!empty($response)) {
    echo json_encode($response);
}