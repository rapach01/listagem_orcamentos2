<?php
// Inclui a conexão com o banco de dados
include '../enviroments/database.php';
include './parts/navbar.php';
include './parts/sidebar.php';
// Busca os materiais no banco de dados
$sql = "SELECT id_material, nome_material FROM materiais";
$stmt = $pdo->query($sql);
$materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estrutura</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilos da sidebar e navbar */
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 60px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;  
            padding: 20px;
            padding-top: 60px;
        }

        /* Responsividade para sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }

            .content {
                margin-left: 100px;
            }

            .sidebar a {
                font-size: 14px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 0;
                display: none;
            }

            .content {
                margin-left: 0;
            }
        }

        /* Estilos do formulário */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .material-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .material-row select, .material-row input {
            width: 48%;
        }

        .add-material-btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .add-material-btn:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        function addMaterialRow() {
            const container = document.getElementById('material-container');
            const materialRow = document.createElement('div');
            materialRow.classList.add('material-row');
            materialRow.innerHTML = `
                <select name="material[]">
                    <option value="">Selecione o material</option>
                    ${document.getElementById('material-options').innerHTML}
                </select>
                <input type="number" name="quantidade[]" placeholder="Quantidade" min="1" required>
            `;
            container.appendChild(materialRow);
        }
    </script>

</head>
<body>

<div class="content">
    <div class="container">
        <h2>Cadastro de Estrutura</h2>
        <form action="../controller/cadastro_estrutura_controller.php" method="POST">
            
            <!-- Nome da Estrutura -->
            <div class="form-group">
                <label for="nome_estrutura">Nome da Estrutura:</label>
                <input type="text" id="nome_estrutura" name="nome_estrutura" placeholder="Nome da Estrutura" required>
            </div>

            <!-- Materiais e Quantidades -->
            <div class="form-group">
                <label for="materiais">Materiais:</label>
                <div id="material-container">
                    <div class="material-row">
                        <select name="material[]">
                            <option value="">Selecione o material</option>
                            <?php foreach($materiais as $material): ?>
                                <option value="<?= $material['id_material']; ?>"><?= htmlspecialchars($material['nome_material']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="quantidade[]" placeholder="Quantidade" min="1" required>
                    </div>
                </div>
                <button type="button" class="add-material-btn" onclick="addMaterialRow()">Adicionar Material</button>
            </div>

            <!-- Botão de Envio -->
            <input type="submit" value="Cadastrar Estrutura">
        </form>
    </div>
</div>

<!-- Hidden div to store material options for use in JavaScript -->
<div id="material-options" style="display:none;">
    <?php foreach($materiais as $material): ?>
        <option value="<?= $material['id_material']; ?>"><?= htmlspecialchars($material['nome_material']); ?></option>
    <?php endforeach; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
