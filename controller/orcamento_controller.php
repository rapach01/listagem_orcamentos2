<?php
// Receba os dados enviados pelo AJAX
// $data = json_decode(file_get_contents('php://input'), true);
// if (!empty($data)) {
//   

//     // Inicialize os arrays para armazenar os resultados
//     $resultadoPostes = [];
//     $resultadoEstruturas1 = []; // Adicionado para estruturas1
//     $resultadoEstruturas2 = []; // Adicionado para estruturas2

//     // Consultar os postes no banco de dados
//     if (!empty($postes)) {
//         // Utilizar placeholders para prevenir injeções SQL
//         $postePlaceholders = implode(',', array_fill(0, count($postes), '?'));
//         $stmtPoste = $pdo->prepare("SELECT * FROM postes WHERE id IN ($postePlaceholders)");
//         $stmtPoste->execute($postes);
//         $resultadoPostes = $stmtPoste->fetchAll(PDO::FETCH_ASSOC);
//     }

//     // Consultar as estruturas1 no banco de dados e buscar os materiais
//     if (!empty($estruturas1)) {
//         foreach ($estruturas1 as $estruturaId) {
//             $stmtEstrutura1 = $pdo->prepare("SELECT * FROM estruturas WHERE id_estrutura = ?");
//             $stmtEstrutura1->execute([$estruturaId]);
//             $estrutura1 = $stmtEstrutura1->fetch(PDO::FETCH_ASSOC);

//             if ($estrutura1) {
//                 $stmtMateriais1 = $pdo->prepare("
//                     SELECT m.nome_material, em.quantidade 
//                     FROM materiais m
//                     JOIN estrutura_materiais em ON m.id_material = em.id_material
//                     WHERE em.id_estrutura = ?
//                 ");
//                 $stmtMateriais1->execute([$estruturaId]);
//                 $materiais1 = $stmtMateriais1->fetchAll(PDO::FETCH_ASSOC);

//                 $estrutura1['materiais'] = array_map(function($material) {
//                     return $material['nome_material'] . ' (Quantidade: ' . $material['quantidade'] . ')';
//                 }, $materiais1);
//                 $resultadoEstruturas1[] = $estrutura1;
//             }
//         }
//     }

//     // Consultar as estruturas2 no banco de dados e buscar os materiais
//     if (!empty($estruturas2)) {
//         foreach ($estruturas2 as $estruturaId) {
//             $stmtEstrutura2 = $pdo->prepare("SELECT * FROM estruturas WHERE id_estrutura = ?");
//             $stmtEstrutura2->execute([$estruturaId]);
//             $estrutura2 = $stmtEstrutura2->fetch(PDO::FETCH_ASSOC);

//             if ($estrutura2) {
//                 $stmtMateriais2 = $pdo->prepare("
//                     SELECT m.nome_material, em.quantidade 
//                     FROM materiais m
//                     JOIN estrutura_materiais em ON m.id_material = em.id_material
//                     WHERE em.id_estrutura = ?
//                 ");
//                 $stmtMateriais2->execute([$estruturaId]);
//                 $materiais2 = $stmtMateriais2->fetchAll(PDO::FETCH_ASSOC);

//                 $estrutura2['materiais'] = array_map(function($material) {
//                     return $material['nome_material'] . ' (Quantidade: ' . $material['quantidade'] . ')';
//                 }, $materiais2);
//                 $resultadoEstruturas2[] = $estrutura2;
//             }
//         }
//     }

//     // Retornar os resultados em formato JSON
//     echo json_encode([
//         'postes' => $resultadoPostes,
//         'estruturas1' => $resultadoEstruturas1, // Adicionado para estruturas1
//         'estruturas2' => $resultadoEstruturas2  // Adicionado para estruturas2
//     ]);
// }
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
        default:
            $response['error'] = 'Ação não encontrada';
        break;
    }
}

if (!empty($response)) {
    echo json_encode($response);
}