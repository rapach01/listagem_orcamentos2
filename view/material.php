<?php 
// Busca os materiais no banco de dados
$sql = "SELECT id_material, nome_material FROM materiais";
$stmt = $pdo->query($sql);
$materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="content">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="text-center">Cadastro de Materiais</h2>
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

                        <div id="materiais-list" class="list-group">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cadastro" role="tabpanel" aria-labelledby="cadastro-tab">
                        <form action="material_controller" method="POST" id="cadastroForm">
                            <input type="hidden" name="action" value="cadastrar">
                            <input type="hidden" id="id_material" name="id_material" value="">
                            <div class="form-group mb-3">
                                <label for="nome_material" class="form-label">Nome do material</label>
                                <input type="text" id="nome_material" name="nome_material" class="form-control" placeholder="Nome" required>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                                <button type="reset" class="btn btn-secondary btn-lg me-md-2" id="reset">
                                    <i class="fas fa-eraser" title="Limpar"></i>
                                </button>
                                <button type="submit" class="btn btn-dark btn-lg ms-md-2 ml-3">
                                    <i class="fas fa-save" title="Cadastrar material"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("reset").addEventListener("click", function() {
            document.getElementById("id_material").value = "";
        });
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const formData = new FormData(this); // Coleta os dados do formulário

            fetch('material_controller', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        loadMateriais(); 
                        document.getElementById('reset').click();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao cadastrar materiais: ' + error);
                });
        });
        // Carregar Materiais ao inicializar
        // loadMateriais();

        // Carregar a lista ao abrir a aba de pesquisa
        document.querySelector('#pesquisa-tab').addEventListener('click', loadMateriais());

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
    // Função para carregar as Materiais
    function loadMateriais() {
        const materiaisList = document.getElementById('materiais-list');
        materiaisList.innerHTML = ''; // Limpa a lista antes de carregar
        fetch('material_controller?action=listar')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    data.data.forEach(materiais => {
                        const listItem = document.createElement('div');
                        listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                        listItem.innerHTML = `
                    ${materiais.nome_material}
                    <div>
                        <button class="btn btn-sm btn-success me-2" onclick="editMaterial(${materiais.id_material})">  <i class="fas fa-edit" title="Editar"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteMaterial(${materiais.id_material})"><i class="fas fa-times" title="Excluir"></i></button>
                    </div>
                `;
                materiaisList.appendChild(listItem);
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
    function editMaterial(id) {
        // Faz a requisição para buscar os dados da estrutura
        fetch(`material_controller?id=${id}&action=editar`)
            .then(response => response.json())
            .then(material => {
                console.log(material);
                // Preenche o nome da material
                document.getElementById('nome_material').value = material.materiais.nome_material;

                // Preencher o campo de ID da material
                document.getElementById('id_material').value = material.materiais.id_material;

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
                console.error('Erro ao carregar dados do material:', error);
            });

    }

    // Função para excluir a materiais
    function deleteMaterial(id) {
        if (confirm('Tem certeza que deseja excluir este material?')) {
            fetch(`material_controller?id=${id}&action=excluir`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message);
                    loadMateriais(); // Recarregar a lista após a exclusão
                });
        }
    }
</script>