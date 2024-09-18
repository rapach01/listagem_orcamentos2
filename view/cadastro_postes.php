<?php 
include './parts/sidebar.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Postes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 50%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input[type="text"], input[type="number"], textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
        select {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-group select:focus {
            border-color: #28a745;
            outline: none;
        }

        .form-group select option {
            padding: 10px;
        }

        /* Custom select arrow */
        .form-group select {
            background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg xmlns%3D%22http://www.w3.org/2000/svg%22 viewBox%3D%220 0 140 140%22%3E%3Cpolygon fill%3D%22%23cccccc%22 points%3D%2270,95 100,65 40,65 %22/%3E%3C/svg%3E');
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 12px 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Postes</h2>
        <form action="../controller/cadastro_postes_controller.php" method="POST">
            <label for="codigo">Código do Poste:</label>
            <input type="text" id="codigo" name="codigo" required>

            <label for="tipo">Altura do Poste (em metros):</label>
            <input type="number" id="tipo" name="tipo" required>

            <label for="parafuso_nivel_1">Nível 1</label>
            <select name="parafuso_nivel_1" id="parafuso_nivel_1">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <label for="parafuso_nivel_2">Nível 1</label>
            <select name="parafuso_nivel_2" id="parafuso_nivel_2">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <input type="submit" value="Cadastrar Poste">
        </form>
    </div>
</body>
</html>
