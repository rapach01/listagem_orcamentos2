<?php
error_reporting(E_ALL);
ini_set('display_errors', value: 1);
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
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
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $materiais = isset($_REQUEST['materiais']) ? $_REQUEST['materiais'] : null;

            if (!empty($materiais)) {
                $greyColor = 'FFCCCCCC';
                $orangeColor = 'ffbb00';

                $sheet->setCellValue('A1', 'Planilha de orçamento ')->getStyle('A1')->getFont()->setBold(true);

                $sheet->getStyle('A1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB($greyColor);

                $sheet->getStyle('A1')->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));

                $sheet->setCellValue('A3', 'Material');
                $sheet->setCellValue('B3', 'Quantidade');
                $sheet->setCellValue('C3', 'Valor');

                $linhaParaEstilizar = 3;

                $style = $sheet->getStyle('A' . $linhaParaEstilizar . ':C' . $linhaParaEstilizar);
                // fonte bold
                $font = $style->getFont();
                $font->setBold(true);
                // bg orange
                $bg = $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $bg->getStartColor()->setARGB($orangeColor);
                // borda
                $style->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));

                $row = 4;

                foreach ($materiais as $material) {
                    $sheet->setCellValue('A' . $row, $material['nome'])->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $sheet->setCellValue('B' . $row, $material['quantidade'])->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $row++;
                }

                for ($column = 'A'; $column <= 'C'; $column++) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $writer = new Xlsx($spreadsheet);
                $filename = 'planilha-orcamento-' . date('d-m-Y') . '-' . date('Hi') . '.xlsx';

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                header('Pragma: public');

                // Limpeza do buffer de saída
                ob_clean();
                flush();

                // Salvar o arquivo para o output
                $writer->save('php://output');
            } else {
                $response['error'] = 'Nenhum material encontrado';
            }
            break;

        case 'pdf':
            $materiais = isset($_REQUEST['materiais']) ? $_REQUEST['materiais'] : null;
            $greyColor = '#a0a6aa';
            $orangeColor = '#ffbb00';
            if (!empty($materiais)) {
                $mpdf = new \Mpdf\Mpdf();

                $html = '<h1 style="text-align: center; background-color: '. $greyColor .'; padding: 10px; border: 1px solid black;">Planilha de Orçamento</h1>';

                $html .= '<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color:'. $orangeColor .'; font-weight: bold;">
                        <th style="border: 1px solid black; padding: 5px;">Material</th>
                        <th style="border: 1px solid black; padding: 5px;">Quantidade</th>
                        <th style="border: 1px solid black; padding: 5px;">Valor</th>
                    </tr>
                </thead>
                <tbody>';

                foreach ($materiais as $material) {
                    $html .= '<tr>
                    <td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($material['nome']) . '</td>
                    <td style="border: 1px solid black; padding: 5px;">' . htmlspecialchars($material['quantidade']) . '</td>
                    <td style="border: 1px solid black; padding: 5px;">' . '</td>
                  </tr>';
                }

                $html .= '</tbody></table>';

                $mpdf->WriteHTML($html);

                $filename = 'planilha-orcamento-' . date('d-m-Y') . '-' . date('Hi') . '.pdf';

                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                header('Pragma: public');

                $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);

            } else {
                $response['error'] = 'Nenhum material encontrado';
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
