<?php
include './parts/sidebar.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Cadastro de Postes</h2>
    <form action="../controller/cadastro_postes_controller.php" method="POST">
        
        <!-- Código do Poste -->
        <div class="mb-3">
            <label for="codigo" class="form-label">Código do Poste:</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required>
        </div>

        <!-- Altura do Poste -->
        <div class="mb-3">
            <label for="tipo" class="form-label">Altura do Poste (em metros):</label>
            <input type="number" class="form-control" id="tipo" name="tipo" required>
        </div>

        <!-- Nível 1 Parafuso -->
        <div class="mb-3">
            <label for="parafuso_nivel_1" class="form-label">Nível 1 Parafuso:</label>
            <select class="form-select" name="parafuso_nivel_1" id="parafuso_nivel_1">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <!-- Nível 2 Parafuso -->
        <div class="mb-3">
            <label for="parafuso_nivel_2" class="form-label">Nível 2 Parafuso:</label>
            <select class="form-select" name="parafuso_nivel_2" id="parafuso_nivel_2">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <!-- Botão de Envio -->
        <div class="d-grid">
            <input type="submit" class="btn btn-secondary" value="Cadastrar Poste">
        </div>
    </form>
</div>

</div>
</div>
</body>

</html>