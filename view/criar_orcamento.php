<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Poste e Estrutura</title>
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

        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            background-color: #fff;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .result-container {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .result-container h3 {
            margin-bottom: 15px;
        }

        .result-container ul {
            list-style-type: none;
            padding: 0;
        }

        .result-container ul li {
            background-color: #fff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Seleção de Poste e Estrutura</h2>
    <form id="postForm">
        <!-- Selecionar Postes -->
        <div class="form-group" id="poste-section">
            <label for="postes">Selecione o Poste:</label>
            <div class="form-row">
                <select name="postes[]" class="poste-select">
                    <option value="poste_1">Poste 1</option>
                    <option value="poste_2">Poste 2</option>
                    <option value="poste_3">Poste 3</option>
                    <option value="poste_4">Poste 4</option>
                </select>
                <button type="button" id="addPoste">Adicionar Poste</button>
            </div>
        </div>

        <!-- Selecionar Estruturas -->
        <div class="form-group" id="estrutura-section">
            <label for="estruturas">Selecione a Estrutura:</label>
            <div class="form-row">
                <select name="estruturas[]" class="estrutura-select">
                    <option value="estrutura_1">Estrutura 1</option>
                    <option value="estrutura_2">Estrutura 2</option>
                    <option value="estrutura_3">Estrutura 3</option>
                    <option value="estrutura_4">Estrutura 4</option>
                </select>
                <button type="button" id="addEstrutura">Adicionar Estrutura</button>
            </div>
        </div>

        <!-- Enviar o formulário -->
        <input type="submit" value="Listar Seleção">
    </form>

    <!-- Resultado da Seleção -->
    <div id="result" class="result-container" style="display: none;">
        <h3>Seleção Feita:</h3>
        <ul id="resultList"></ul>
    </div>
</div>

<script>
    document.getElementById('addPoste').addEventListener('click', function() {
        // Criar um novo campo de seleção de poste
        const posteSection = document.getElementById('poste-section');
        const newPosteRow = document.createElement('div');
        newPosteRow.classList.add('form-row');
        newPosteRow.innerHTML = `
            <select name="postes[]" class="poste-select">
                <option value="poste_1">Poste 1</option>
                <option value="poste_2">Poste 2</option>
                <option value="poste_3">Poste 3</option>
                <option value="poste_4">Poste 4</option>
            </select>
            <button type="button" class="removePoste">Remover</button>
        `;
        posteSection.appendChild(newPosteRow);

        // Adicionar evento de remoção
        newPosteRow.querySelector('.removePoste').addEventListener('click', function() {
            posteSection.removeChild(newPosteRow);
        });
    });

    document.getElementById('addEstrutura').addEventListener('click', function() {
        // Criar um novo campo de seleção de estrutura
        const estruturaSection = document.getElementById('estrutura-section');
        const newEstruturaRow = document.createElement('div');
        newEstruturaRow.classList.add('form-row');
        newEstruturaRow.innerHTML = `
            <select name="estruturas[]" class="estrutura-select">
                <option value="estrutura_1">Estrutura 1</option>
                <option value="estrutura_2">Estrutura 2</option>
                <option value="estrutura_3">Estrutura 3</option>
                <option value="estrutura_4">Estrutura 4</option>
            </select>
            <button type="button" class="removeEstrutura">Remover</button>
        `;
        estruturaSection.appendChild(newEstruturaRow);

        // Adicionar evento de remoção
        newEstruturaRow.querySelector('.removeEstrutura').addEventListener('click', function() {
            estruturaSection.removeChild(newEstruturaRow);
        });
    });

    document.getElementById('postForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        // Obter valores dos postes e estruturas selecionados
        const postes = Array.from(document.querySelectorAll('.poste-select')).map(select => select.value);
        const estruturas = Array.from(document.querySelectorAll('.estrutura-select')).map(select => select.value);

        // Atualizar lista de resultados
        const resultList = document.getElementById('resultList');
        resultList.innerHTML = ''; // Limpar resultados anteriores

        // Adicionar postes e estruturas selecionados na lista
        postes.forEach((poste, index) => {
            resultList.innerHTML += `<li>Poste ${index + 1}: ${poste.replace('_', ' ')}</li>`;
        });

        estruturas.forEach((estrutura, index) => {
            resultList.innerHTML += `<li>Estrutura ${index + 1}: ${estrutura.replace('_', ' ')}</li>`;
        });

        // Exibir a div de resultados
        document.getElementById('result').style.display = 'block';
    });
</script>

</body>
</html>
