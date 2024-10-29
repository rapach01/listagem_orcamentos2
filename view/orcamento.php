<style>
    .form-select {
        background-color: #f8f9fa;
        /* Cor de fundo leve */
        border: 1px solid #ced4da;
        /* Borda leve */
        border-radius: 4px;
        /* Borda arredondada */
        padding: 8px 12px;
        /* Espaçamento interno */
        font-size: 14px;
        /* Tamanho da fonte */
        color: #495057;
        /* Cor do texto */
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        /* Efeito de transição */
        width: 100%;
    }

    .form-select:focus {
        border-color: #007bff;
        /* Cor da borda ao focar */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        /* Sombra leve ao focar */
        outline: none;
        /* Remove o contorno padrão */
    }

    .form-select option {
        color: #495057;
        /* Cor das opções */
        background-color: #fff;
        /* Cor de fundo das opções */
    }

    .result-container {
        background-color: #f8f9fa;
        /* Cor de fundo suave */
        border: 1px solid #dee2e6;
        /* Borda clara */
        border-radius: 0.5rem;
        /* Bordas arredondadas */
        padding: 20px;
        /* Espaçamento interno */
        margin-top: 1rem;
        /* Espaçamento acima */
    }

    .list-group-item {
        font-size: 1.1rem;
        /* Aumenta a fonte */
    }

    .table th {
        background-color: #009A72;
        /* Cor de fundo do cabeçalho */
        color: white;
        /* Cor do texto do cabeçalho */
        text-align: center;
        /* Centraliza o texto */
    }

    .table td {
        vertical-align: middle;
        /* Alinha o conteúdo verticalmente ao centro */
    }

    #addRow {
        margin-top: 1rem;
        /* Espaçamento acima do botão de adicionar */
    }
</style>

<div class="container mt-5">
    <div class="card" style="height: auto;">
        <div class="card-header">
            <h2 class="text-center mb-0">Seleção de Poste e Estrutura</h2>
        </div>
        <div class="card-body">
            <form id="postForm">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th>Estrutura 1</th>
                            <th>Parafuso Nível 1</th>
                            <th>Estrutura 2</th>
                            <th>Parafuso Nível 2</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="poste-estrutura-table">
                        <tr>
                            <td>
                                <select name="postes[0]" class="form-select posteSelect" id="posteSelect">
                                    <option value="" disabled selected>Selecione um poste</option>
                                    <?=
                                    // Listar os postes disponíveis
                                    $sql = "SELECT * FROM postes";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $poste) {
                                        echo "<option value='{$poste['id_poste']}'>{$poste['codigo']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="estruturas1[0]" class="form-select estruturaSelect">
                                    <option value="" disabled selected>Selecione a estrutura 1</option>
                                    <?php
                                    // Listar as estruturas disponíveis
                                    $sql = "SELECT * FROM estruturas";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $estrutura) {
                                        echo "<option value='{$estrutura['id_estrutura']}'>{$estrutura['descricao_estrutura']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" readonly class="form-control parafuso-nivel-1"
                                    id="parafuso_nivel_1" name="parafuso_nivel_1[0]" placeholder="Tam. Parafuso">
                            </td>
                            <td>
                                <select name="estruturas2[]" class="form-select estruturaSelect">
                                    <option value="" disabled selected>Selecione a estrutura 2</option>
                                    <?php
                                    // Listar as estruturas disponíveis
                                    $sql = "SELECT * FROM estruturas";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $estrutura) {
                                        echo "<option value='{$estrutura['id_estrutura']}'>{$estrutura['descricao_estrutura']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" readonly class="form-control parafuso-nivel-2"
                                    id="parafuso_nivel_2" name="parafuso_nivel_2[0]" placeholder="Tam. Parafuso">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-secondary" id="addRow" title="Adicionar Poste e Estruturas">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary" title="Listar Seleção">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="card" style="height: auto;">
        <div class="card-header">
            <h2 class="text-center mb-0">Materiais para Estruturas Selecionadas</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome do Material</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody id="materiais-table">
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">
                            <button type="button" class="btn btn-success btn-lg me-md-2" id="xlsx" title=" Gerar XLSX">
                                <i class="fas fa-file-csv" title="Gerar XLSX"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-lg me-md-2 ml-3" id="pdf" title="Gerar PDF">
                                <i class="fas fa-file-pdf" title="Gerar PDF"></i>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = 1;

        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.getElementById('poste-estrutura-table');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
            <td>
                <select name="postes[${rowCount}]" class="form-select posteSelect">
                    <option value="" disabled selected>Selecione um poste</option>
                    <?php
                    $sql = "SELECT * FROM postes";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $poste) {
                        echo "<option value='{$poste['id_poste']}'>{$poste['codigo']}</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="estruturas1[${rowCount}]" class="form-select estruturaSelect">
                    <option value="" disabled selected>Selecione a estrutura 1</option>
                    <?php
                    $sql = "SELECT * FROM estruturas";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $estrutura) {
                        echo "<option value='{$estrutura['id_estrutura']}'>{$estrutura['descricao_estrutura']}</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" readonly class="form-control parafuso-nivel-1" name="parafuso_nivel_1[${rowCount}]" placeholder="Tam. Parafuso">
            </td>
            <td>
                <select name="estruturas2[${rowCount}]" class="form-select estruturaSelect">
                    <option value="" disabled selected>Selecione a estrutura 2</option>
                    <?php
                    $sql = "SELECT * FROM estruturas";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $estrutura) {
                        echo "<option value='{$estrutura['id_estrutura']}'>{$estrutura['descricao_estrutura']}</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" readonly class="form-control parafuso-nivel-2" name="parafuso_nivel_2[${rowCount}]" placeholder="Tam. Parafuso">
            </td>
            <td>
                <button type="button" class="btn btn-danger removeRow"><i class="fas fa-times"></i></button>
            </td>
        `;

            tableBody.appendChild(newRow);

            newRow.querySelector('.removeRow').addEventListener('click', function() {
                tableBody.removeChild(newRow);
                rowCount--;
            });

            newRow.querySelector('.posteSelect').addEventListener('change', function() {
                const posteId = this.value;
                const row = this.closest('tr');

                if (posteId) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'orcamento_controller', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                const data = JSON.parse(xhr.responseText);
                                if (data.postes.length > 0) {
                                    row.querySelector('.parafuso-nivel-1').value = data.postes[0].parafuso_nivel_1 || '';
                                    row.querySelector('.parafuso-nivel-2').value = data.postes[0].parafuso_nivel_2 || '';
                                } else {
                                    row.querySelector('.parafuso-nivel-1').value = '';
                                    row.querySelector('.parafuso-nivel-2').value = '';
                                }
                            } else {
                                console.error('There was a problem with the request.');
                            }
                        }
                    };
                    xhr.send(`id=${posteId}&action=buscarPostes`);
                } else {
                    row.querySelector('.parafuso-nivel-1').value = '';
                    row.querySelector('.parafuso-nivel-2').value = '';
                }
            });

            rowCount++;
        });

        document.getElementById('postForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const dados = new URLSearchParams(formData).toString() + "&action=listar";

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'orcamento_controller', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const data = JSON.parse(xhr.responseText);
                        const materiaisTable = document.getElementById('materiais-table');
                        materiaisTable.innerHTML = '';

                        const materiaisCombinados = {};
                        const postesCombinados = {};

                        data.data.forEach(item => {
                            const agregarMateriais = (materiais, tamanhoParafuso) => {
                                materiais.forEach(material => {
                                    let nomeMaterial = material.nome_material;

                                    // Verificar se é um parafuso e aplicar o tamanho correspondente
                                    if (nomeMaterial.toLowerCase() === 'parafuso') {
                                        nomeMaterial += ` Tamanho ${tamanhoParafuso}`;
                                    }

                                    if (materiaisCombinados[nomeMaterial]) {
                                        materiaisCombinados[nomeMaterial].quantidade += material.quantidade;
                                    } else {
                                        materiaisCombinados[nomeMaterial] = {
                                            nome_material: nomeMaterial,
                                            quantidade: material.quantidade
                                        };
                                    }
                                });
                            };

                            // Agregar materiais com o tamanho do parafuso correspondente
                            agregarMateriais(item.materiais_estrutura1, item.parafuso_nivel_1);
                            agregarMateriais(item.materiais_estrutura2, item.parafuso_nivel_2);

                            const tipoPoste = item.tipo_poste;
                            if (postesCombinados[tipoPoste]) {
                                postesCombinados[tipoPoste].quantidade += 1;
                            } else {
                                postesCombinados[tipoPoste] = {
                                    tipo_poste: tipoPoste,
                                    quantidade: 1
                                };
                            }
                        });

                        // Adicionar materiais à tabela
                        Object.values(materiaisCombinados).forEach(material => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
        <td>${material.nome_material}</td>
        <td>${material.quantidade}</td>
        `;
                            materiaisTable.appendChild(row);
                        });

                        // Adicionar postes à tabela
                        Object.values(postesCombinados).forEach(poste => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
        <td>${poste.tipo_poste}</td>
        <td>${poste.quantidade}</td>
        `;
                            materiaisTable.appendChild(row);
                        });
                    } else {
                        console.error('There was a problem with the request.');
                    }

                }
            };
            xhr.send(dados);
        });

        document.getElementById('xlsx').addEventListener('click', function(e) {
            e.preventDefault();

            const materiais = [];
            const rows = document.querySelectorAll('#materiais-table tr');

            rows.forEach(row => {
                const nomeMaterial = row.cells[0].textContent.trim();
                const quantidade = row.cells[1].textContent.trim();
                if (nomeMaterial && quantidade) {
                    materiais.push({
                        nome: nomeMaterial,
                        quantidade: quantidade
                    });
                }
            });

            const params = new URLSearchParams();
            params.append('action', 'xlsx');

            materiais.forEach((material, index) => {
                params.append(`materiais[${index}][nome]`, material.nome);
                params.append(`materiais[${index}][quantidade]`, material.quantidade);
            });

            const url = 'orcamento_controller?' + params.toString();

            window.open(url, '_blank');
        });

        document.getElementById('pdf').addEventListener('click', function(e) {
            e.preventDefault();

            const materiais = [];
            const rows = document.querySelectorAll('#materiais-table tr');

            rows.forEach(row => {
                const nomeMaterial = row.cells[0].textContent.trim();
                const quantidade = row.cells[1].textContent.trim();
                if (nomeMaterial && quantidade) {
                    materiais.push({
                        nome: nomeMaterial,
                        quantidade: quantidade
                    });
                }
            });

            const params = new URLSearchParams();
            params.append('action', 'pdf');

            materiais.forEach((material, index) => {
                params.append(`materiais[${index}][nome]`, material.nome);
                params.append(`materiais[${index}][quantidade]`, material.quantidade);
            });

            const url = 'orcamento_controller?' + params.toString();

            window.open(url, '_blank');
        });

        document.getElementById('posteSelect').addEventListener('change', function() {
            const posteId = this.value;

            if (posteId) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'orcamento_controller', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            if (data.postes.length > 0) {
                                document.getElementById('parafuso_nivel_1').value = data.postes[0].parafuso_nivel_1 || '';
                                document.getElementById('parafuso_nivel_2').value = data.postes[0].parafuso_nivel_2 || '';
                            } else {
                                document.getElementById('parafuso_nivel_1').value = '';
                                document.getElementById('parafuso_nivel_2').value = '';
                            }
                        } else {
                            console.error('There was a problem with the request.');
                        }
                    }
                };
                xhr.send(`id=${posteId}&action=buscarPostes`);
            } else {
                document.getElementById('parafuso_nivel_1').value = '';
                document.getElementById('parafuso_nivel_2').value = '';
            }
        });
    });
</script>