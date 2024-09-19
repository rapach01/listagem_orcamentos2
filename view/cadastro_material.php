<?php
include './parts/sidebar.php';

?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Cadastro de Materiais</h2>
    <form action="../controller/cadastro_material_controller.php" method="POST">
        
        <!-- Nome do Material -->
        <div class="mb-3">
            <label for="nome_material" class="form-label">Nome do Material:</label>
            <input type="text" class="form-control" id="nome_material" name="nome_material" placeholder="Ex: Concreto, Aço, Madeira" required>
        </div>

        <!-- Tipo de Material -->
        <div class="mb-3">
            <label for="tipo_material" class="form-label">Tipo de Material:</label>
            <select id="tipo_material" name="tipo_material" class="form-select" required>
                <option value="">Selecione o tipo de material</option>
                <option value="base">Base</option>
                <option value="corpo">Corpo</option>
                <option value="parafuso">Parafuso</option>
                <option value="cabo">Cabo</option>
                <!-- Adicione mais tipos conforme necessário -->
            </select>
        </div>

        <!-- Botão de Enviar -->
        <div class="d-grid">
            <input type="submit" class="btn btn-secondary" value="Cadastrar Material">
        </div>
    </form>
</div>
</div>
</div>
</body>
</html>
