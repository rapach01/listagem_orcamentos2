<?php
// Busca os materiais no banco de dados
$sql = "SELECT * FROM postes";
$stmt = $pdo->query($sql);
$materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="text-center">Cadastro de Postes</h2>
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
                        <div id="postes-list" class="list-group"></div>
                    </div>

                    <div class="tab-pane fade" id="cadastro" role="tabpanel" aria-labelledby="cadastro-tab">
                        <form action="postes_controller" method="POST" id="cadastroForm">
                            <input type="hidden" name="action" value="cadastrar">
                            <input type="hidden" id="id_poste" name="id_poste" value="">

                            <!-- Nome do Poste -->
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Nome do Poste:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>

                            <!-- Altura do Poste -->
                            <div class="mb-3">
                                <label for="altura" class="form-label">Altura do Poste (em metros):</label>
                                <input type="number" class="form-control" id="altura" name="altura" required>
                            </div>

                            <!-- Tipo do Poste -->
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo:</label>
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

                            <!-- Botões -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                                <button type="reset" class="btn btn-secondary btn-lg me-md-2">
                                    <i class="fas fa-eraser" alt="Limpar"></i> <!-- Ícone de reset -->
                                </button>
                                <button type="submit" class="btn btn-dark btn-lg ms-md-2 ml-3">
                                    <i class="fas fa-save" alt="Cadastrar Poste"></i> <!-- Ícone de cadastro -->
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('cadastroForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const formData = new FormData(this); // Coleta os dados do formulário

            fetch('postes_controller', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        loadPostes(); // Atualiza a lista de postes
                        this.reset(); // Limpa o formulário
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao cadastrar postes: ' + error);
                });
        });
        // Carregar postes ao inicializar
        // loadpostes();

        // Carregar a lista ao abrir a aba de pesquisa
        document.querySelector('#pesquisa-tab').addEventListener('click', loadPostes());

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
    // Função para carregar as postes
    function loadPostes() {
        const postesList = document.getElementById('postes-list');
        postesList.innerHTML = ''; // Limpa a lista antes de carregar
        fetch('postes_controller?action=listar')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    data.data.forEach(postes => {
                        const listItem = document.createElement('div');
                        listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                        listItem.innerHTML = `
                    ${postes.codigo}
                    <div>
                        <button class="btn btn-sm btn-primary me-2" onclick="editPoste(${postes.id_poste})">
                            <i class="fas fa-edit" alt="Editar"></i> 
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deletePoste(${postes.id_poste})">
                            <i class="fas fa-times" alt="Excluir"></i> 
                        </button>
                    </div>

                `;
                        postesList.appendChild(listItem);
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
    function editPoste(id) {
        // Faz a requisição para buscar os dados da estrutura
        fetch(`postes_controller?id=${id}&action=editar`)
            .then(response => response.json())
            .then(poste => {
                console.log(poste);
                // Preenche o nome da poste
                document.getElementById('codigo').value = poste.postes.codigo;
                document.getElementById('id_poste').value = poste.postes.id_poste;
                document.getElementById('altura').value = poste.postes.altura;
                document.getElementById('tipo').value = poste.postes.tipo;
                document.getElementById('parafuso_nivel_1').value = poste.postes.parafuso_nivel_1;
                document.getElementById('parafuso_nivel_2').value = poste.postes.parafuso_nivel_2;
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
                console.error('Erro ao carregar dados do poste:', error);
            });

    }

    // Função para excluir a postes
    function deletePoste(id) {
        if (confirm('Tem certeza que deseja excluir este poste?')) {
            fetch(`postes_controllerid=${id}&action=excluir`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message);
                    loadPostes(); // Recarregar a lista após a exclusão
                });
        }
    }
</script>