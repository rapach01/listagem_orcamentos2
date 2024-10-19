<?php

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

    /* Ajuste geral para o formulário */
    #cadastroForm {
        background-color: #f8f9fa;
        /* Cor de fundo suave */
        padding: 20px;
        /* Espaçamento interno */
        border-radius: 8px;
        /* Bordas arredondadas */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /* Sombra suave */
    }

    /* Ajuste para o campo de input e select */
    #cadastroForm .form-control,
    #cadastroForm .form-select {
        border-radius: 5px;
        /* Bordas arredondadas */
        padding: 10px 15px;
        /* Espaçamento interno */
        font-size: 16px;
        /* Tamanho da fonte */
        border: 1px solid #ced4da;
        /* Cor da borda */
        transition: all 0.3s ease;
        /* Transição suave ao focar */
    }

    #cadastroForm .form-control:focus,
    #cadastroForm .form-select:focus {
        border-color: #007bff;
        /* Cor da borda ao focar */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        /* Sombra ao focar */
        outline: none;
        /* Remove o outline padrão */
    }

    /* Botão adicionar material */
    #cadastroForm .add-material-btn {
        color: white;
        /* Cor do texto */
        border: none;
        /* Remove bordas */
        padding: 10px 20px;
        /* Espaçamento interno */
        font-size: 16px;
        /* Tamanho da fonte */
        border-radius: 5px;
        /* Bordas arredondadas */
        transition: background-color 0.3s ease;
        /* Transição suave */
    }

    /* Espaçamento entre seções */
    .form-group {
        margin-bottom: 20px;
    }
</style>
<div class="content">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="text-center">Cadastro de Estruturas</h2>
            </div>
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

                        <div id="estrutura-list" class="list-group">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cadastro" role="tabpanel" aria-labelledby="cadastro-tab">
                        <form action="estrutura_controller" method="POST" id="cadastroForm">
                            <input type="hidden" name="action" value="cadastrar">
                            <input type="hidden" id="id_estrutura" name="id_estrutura" value="">
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
    // Função para adicionar uma linha de material
    function addMaterialRow(materialId = '', quantidade = '') {
        const materialContainer = document.getElementById('material-container');
        const materialRow = `
        <div class="row mb-2 material-row">
            <div class="col-md-8">
                <select name="material[]" class="form-select" required>
                    <option value="">Selecione o material</option>
                    ${materiais.map(material => `
                        <option value="${material.id_material}" ${materialId == material.id_material ? 'selected' : ''}>
                            ${material.nome_material}
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantidade[]" class="form-control" placeholder="Quantidade" min="1" value="${quantidade}" required>
            </div>
        </div>
    `;
        materialContainer.insertAdjacentHTML('beforeend', materialRow);
    }
    const materiais = <?= json_encode($materiais); ?>;
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const formData = new FormData(this); // Coleta os dados do formulário

            fetch('estrutura_controller', {
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
    // Função para carregar as estruturas
    function loadEstruturas() {
        const estruturaList = document.getElementById('estrutura-list');
        estruturaList.innerHTML = ''; // Limpa a lista antes de carregar

        fetch('estrutura_controller?action=listar')
            .then(response => response.json())
            .then(data => {
                console.log
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
        // Faz a requisição para buscar os dados da estrutura
        fetch(`estrutura_controller?id=${id}&action=editar`)
            .then(response => response.json())
            .then(estrutura => {
                console.log(estrutura);
                // Preenche o nome da estrutura
                document.getElementById('nome_estrutura').value = estrutura.estrutura.descricao_estrutura;

                // Limpar materiais antigos e preencher com os novos dados
                const materialContainer = document.getElementById('material-container');
                materialContainer.innerHTML = ''; // Limpa o conteúdo atual

                estrutura.materiais.forEach(material => {
                    addMaterialRow(material.id_material, material.quantidade); // Adiciona a linha de material com dados preenchidos
                });

                // Preencher o campo de ID da estrutura
                document.getElementById('id_estrutura').value = estrutura.estrutura.id_estrutura;

                // Ativar a aba de cadastro programaticamente
                var tabTrigger = new bootstrap.Tab(document.querySelector('#cadastro-tab'));
                tabTrigger.show();

                // Garantir que o conteúdo da aba seja exibido corretamente
                const cadastroTabContent = document.querySelector('#cadastro'); // ID do conteúdo da aba
                const otherTabContent = document.querySelectorAll('.tab-pane'); // Todas as outras abas

                // Remove as classes "show" e "active" das outras abas
                otherTabContent.forEach(tab => {
                    tab.classList.remove('show', 'active');
                });

                // Adiciona as classes "show" e "active" à aba de cadastro
                cadastroTabContent.classList.add('show', 'active');

            })
            .catch(error => {
                console.error('Erro ao carregar dados da estrutura:', error);
            });

    }

    // Função para excluir a estrutura
    function deleteEstrutura(id) {
        if (confirm('Tem certeza que deseja excluir esta estrutura?')) {
            fetch(`estrutura?id=${id}&action=excluir`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message);
                    loadEstruturas(); // Recarregar a lista após a exclusão
                });
        }
    }
</script>
</body>

</html>