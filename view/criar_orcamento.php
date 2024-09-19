<?php
include './parts/sidebar.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Seleção de Poste e Estrutura</h2>
    <form id="postForm">

        <!-- Selecionar Postes -->
        <div class="form-group" id="poste-section">
            <label for="postes">Selecione o Poste:</label>
            <div class="input-group mb-3">
                <select name="postes[]" class="form-select">
                    <option value="poste_1">Poste 1</option>
                    <option value="poste_2">Poste 2</option>
                    <option value="poste_3">Poste 3</option>
                    <option value="poste_4">Poste 4</option>
                </select>
                <button type="button" class="btn btn-secondary ms-2" id="addPoste">Adicionar Poste</button>
            </div>
        </div>

        <!-- Selecionar Estruturas -->
        <div class="form-group" id="estrutura-section">
            <label for="estruturas">Selecione a Estrutura:</label>
            <div class="input-group mb-3">
                <select name="estruturas[]" class="form-select">
                    <option value="estrutura_1">Estrutura 1</option>
                    <option value="estrutura_2">Estrutura 2</option>
                    <option value="estrutura_3">Estrutura 3</option>
                    <option value="estrutura_4">Estrutura 4</option>
                </select>
                <button type="button" class="btn btn-secondary ms-2" id="addEstrutura">Adicionar Estrutura</button>
            </div>
        </div>

        <!-- Enviar o formulário -->
        <div class="d-grid">
            <input type="submit" class="btn btn-secondary" value="Listar Seleção">
        </div>
    </form>

    <!-- Resultado da Seleção -->
    <div id="result" class="result-container mt-4" style="display: none;">
        <h3>Seleção Feita:</h3>
        <ul id="resultList" class="list-group"></ul>
    </div>
</div>

<script>
    document.getElementById('addPoste').addEventListener('click', function() {
        const posteSection = document.getElementById('poste-section');
        const newPosteRow = document.createElement('div');
        newPosteRow.classList.add('input-group', 'mb-3');
        newPosteRow.innerHTML = `
            <select name="postes[]" class="form-select">
                <option value="poste_1">Poste 1</option>
                <option value="poste_2">Poste 2</option>
                <option value="poste_3">Poste 3</option>
                <option value="poste_4">Poste 4</option>
            </select>
            <button type="button" class="btn btn-danger ms-2 removePoste">Remover</button>
        `;
        posteSection.appendChild(newPosteRow);

        newPosteRow.querySelector('.removePoste').addEventListener('click', function() {
            posteSection.removeChild(newPosteRow);
        });
    });

    document.getElementById('addEstrutura').addEventListener('click', function() {
        const estruturaSection = document.getElementById('estrutura-section');
        const newEstruturaRow = document.createElement('div');
        newEstruturaRow.classList.add('input-group', 'mb-3');
        newEstruturaRow.innerHTML = `
            <select name="estruturas[]" class="form-select">
                <option value="estrutura_1">Estrutura 1</option>
                <option value="estrutura_2">Estrutura 2</option>
                <option value="estrutura_3">Estrutura 3</option>
                <option value="estrutura_4">Estrutura 4</option>
            </select>
            <button type="button" class="btn btn-danger ms-2 removeEstrutura">Remover</button>
        `;
        estruturaSection.appendChild(newEstruturaRow);

        newEstruturaRow.querySelector('.removeEstrutura').addEventListener('click', function() {
            estruturaSection.removeChild(newEstruturaRow);
        });
    });

    document.getElementById('postForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const postes = Array.from(document.querySelectorAll('.poste-select')).map(select => select.value);
        const estruturas = Array.from(document.querySelectorAll('.estrutura-select')).map(select => select.value);

        const resultList = document.getElementById('resultList');
        resultList.innerHTML = '';

        postes.forEach((poste, index) => {
            resultList.innerHTML += `<li class="list-group-item">Poste ${index + 1}: ${poste.replace('_', ' ')}</li>`;
        });

        estruturas.forEach((estrutura, index) => {
            resultList.innerHTML += `<li class="list-group-item">Estrutura ${index + 1}: ${estrutura.replace('_', ' ')}</li>`;
        });

        document.getElementById('result').style.display = 'block';
    });
</script>
</div>
</div>

</body>
</html>
