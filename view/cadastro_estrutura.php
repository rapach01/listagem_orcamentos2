<?php
// Inclui a conexão com o banco de dados
include '../enviroments/database.php';
include './parts/sidebar.php';
// Busca os materiais no banco de dados
$sql = "SELECT id_material, nome_material FROM materiais";
$stmt = $pdo->query($sql);
$materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .add-material-btn {
        margin-top: 10px;
    }

    .material-row {
        margin-bottom: 10px;
    }
</style>
<div class="content">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pesquisa-tab" data-bs-toggle="tab" data-bs-target="#pesquisa"
                            type="button" role="tab" aria-controls="pesquisa" aria-selected="true">Pesquisa</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cadastro-tab" data-bs-toggle="tab" data-bs-target="#cadastro"
                            type="button" role="tab" aria-controls="cadastro" aria-selected="false">Cadastro</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pesquisa" role="tabpanel" aria-labelledby="pesquisa-tab">
                        <h3 class="text-center mt-4">Lista de Estruturas</h3>
                        <div id="estrutura-list" class="list-group">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cadastro" role="tabpanel" aria-labelledby="cadastro-tab">
                        <h3 class="text-center mt-4">Cadastro de Estrutura</h3>
                        <form action="../controller/cadastro_estrutura_controller.php" method="POST" id="cadastroForm">
                            <div class="form-group mb-3">
                                <label for="nome_estrutura" class="form-label">Nome da Estrutura:</label>
                                <input type="text" id="nome_estrutura" name="nome_estrutura" class="form-control" placeholder="Nome da Estrutura" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="materiais" class="form-label">Materiais:</label>
                                <div id="material-container">
                                    <div class="row mb-2 material-row">
                                        <div class="col-md-8">
                                            <select name="material[]" class="form-select">
                                                <option value="">Selecione o material</option>
                                                <?php foreach ($materiais as $material): ?>
                                                    <option value="<?= $material['id_material']; ?>"><?= htmlspecialchars($material['nome_material']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="quantidade[]" class="form-control" placeholder="Quantidade" min="1" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary w-100 mt-3 add-material-btn" onclick="addMaterialRow()">Adicionar Material</button>
                            </div>
                            <div class="d-grid">
                                <input type="submit" value="Cadastrar Estrutura" class="btn btn-dark">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Hidden div to store material options -->
<div id="material-options" style="display:none;">
    <?php foreach ($materiais as $material): ?>
        <option value="<?= $material['id_material']; ?>"><?= htmlspecialchars($material['nome_material']); ?></option>
    <?php endforeach; ?>
</div>

</div>
</div>
<script>
    function addMaterialRow() {
        const container = document.getElementById('material-container');

        const materialRow = document.createElement('div');
        materialRow.classList.add('row', 'mb-2', 'material-row');

        materialRow.innerHTML = `
            <div class="col-md-8">
                <select name="material[]" class="form-select">
                    <option value="">Selecione o material</option>
                    ${document.getElementById('material-options').innerHTML}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantidade[]" class="form-control" placeholder="Quantidade" min="1" required>
            </div>
        `;
        container.appendChild(materialRow);
    }
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const formData = new FormData(this); // Coleta os dados do formulário

            fetch('http://localhost/listagem_orcamentos/listagem_orcamentos2/controller/cadastro_estrutura_controller.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        loadEstruturas(); // Atualiza a lista de estruturas
                        this.reset(); // Limpa o formulário
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao cadastrar estrutura: ' + error);
                });
        });

        // Função para carregar as estruturas
        function loadEstruturas() {
            const estruturaList = document.getElementById('estrutura-list');
            estruturaList.innerHTML = ''; // Limpa a lista antes de carregar

            fetch('http://localhost/listagem_orcamentos/listagem_orcamentos2/controller/cadastro_estrutura_controller.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        data.data.forEach(estrutura => {
                            const listItem = document.createElement('div');
                            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            listItem.innerHTML = `
                    ${estrutura.descricao_estrutura}
                    <div>
                        <button class="btn btn-sm btn-primary me-2" onclick="editEstrutura(${estrutura.id_estrutura})">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteEstrutura(${estrutura.id_estrutura})">Excluir</button>
                    </div>
                `;
                            estruturaList.appendChild(listItem);
                        });
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao carregar estruturas: ' + error);
                });
        }

        // Função para editar a estrutura
        function editEstrutura(id) {
            fetch(`http://localhost/listagem_orcamentos/listagem_orcamentos2/controller/cadastro_estrutura_controller.php?id=${id}`)
                .then(response => response.json())
                .then(estrutura => {
                    document.getElementById('nome_estrutura').value = estrutura.descricao_estrutura;
                    // Limpar materiais antigos e preencher os novos
                    const materialContainer = document.getElementById('material-container');
                    materialContainer.innerHTML = '';
                    estrutura.materiais.forEach(material => {
                        addMaterialRow(material.id_material, material.quantidade); // Assumindo que você tenha essa função
                    });
                    // Mudar para a aba de cadastro
                    var tabTrigger = new bootstrap.Tab(document.querySelector('#cadastro-tab'));
                    tabTrigger.show();
                });
        }

        // Função para excluir a estrutura
        function deleteEstrutura(id) {
            if (confirm('Tem certeza que deseja excluir esta estrutura?')) {
                fetch(`http://localhost/listagem_orcamentos/listagem_orcamentos2/controller/cadastro_estrutura_controller.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(result => {
                        alert(result.message);
                        loadEstruturas(); // Recarregar a lista após a exclusão
                    });
            }
        }

        // Carregar estruturas ao inicializar
        loadEstruturas();

        // Carregar a lista ao abrir a aba de pesquisa
        document.querySelector('#pesquisa-tab').addEventListener('click', loadEstruturas);

        const tabLinks = document.querySelectorAll('.nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remover a classe active de todos os links e panes
                tabLinks.forEach(item => item.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('show', 'active'));

                // Adicionar a classe active ao link clicado
                this.classList.add('active');

                // Ativar o pane correspondente
                const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
                targetPane.classList.add('show', 'active');
            });
        });


    });
</script>
</body>

</html>