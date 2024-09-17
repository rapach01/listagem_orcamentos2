<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Materiais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
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

        input[type="text"], select {
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
    </style>
</head>
<body>

<div class="container">
    <h2>Cadastro de Materiais</h2>
    <form action="../controller/cadastro_material_controller.php" method="POST">
        
        <!-- Nome do Material -->
        <div class="form-group">
            <label for="nome_material">Nome do Material:</label>
            <input type="text" id="nome_material" name="nome_material" placeholder="Ex: Concreto, Aço, Madeira" required>
        </div>

        <!-- Tipo do Material -->
        <div class="form-group">
            <label for="tipo_material">Tipo de Material:</label>
            <select id="tipo_material" name="tipo_material" required>
                <option value="">Selecione o tipo de material</option>
                <option value="base">Base</option>
                <option value="corpo">Corpo</option>
                <option value="parafuso">Parafuso</option>
                <option value="cabo">Cabo</option>
                <!-- Adicione mais tipos conforme necessário -->
            </select>
        </div>

        <!-- Enviar o formulário -->
        <input type="submit" value="Cadastrar Material">
    </form>
</div>

</body>
</html>
