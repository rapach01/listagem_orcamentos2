<style>
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
</style>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header">
            <h2 class="text-center mb-0">Seleção de Poste e Estrutura</h2>
        </div>
        <div class="card-body">
            <form id="postForm">
                <input type="hidden" name="action" value="listar">
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
                                <select name="postes[]" id="posteSelect" class="form-select">
                                    <option value="" disabled selected>Selecione um poste</option>
                                    <?php
                                    // Listar os postes disponíveis
                                    $sql = "SELECT * FROM postes";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $poste) {
                                        echo "<option value='{$poste['id']}'>{$poste['codigo']}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="estruturas1[]" class="form-select">
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
                                <input type="number" readonly id="parafuso_nivel_1" placeholder="Tam. Parafuso">
                            </td>
                            <td>
                                <select name="estruturas2[]" class="form-select">
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
                                <input type="number" readonly id="parafuso_nivel_2" placeholder="Tam. Parafuso">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger removeRow">Remover</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary" id="addRow">Adicionar Poste e Estruturas</button>

                <!-- Enviar o formulário -->
                <div class="d-grid mt-3">
                    <input type="submit" class="btn btn-primary" value="Listar Seleção">
                </div>
            </form>

            <!-- Resultado da Seleção -->
            <div id="result" class="result-container mt-4" style="display: none;">
                <h3>Seleção Feita:</h3>
                <ul id="resultList" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addRow').addEventListener('click', function() {
            const tableBody = document.getElementById('poste-estrutura-table');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                    <td>
                        <select name="postes[]" class="form-select posteSelect">
                            <option value="" disabled selected>Selecione um poste</option>
                            <?php
                            // Listar os postes disponíveis
                            $sql = "SELECT * FROM postes";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $poste) {
                                echo "<option value='{$poste['id']}'>{$poste['codigo']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="estruturas1[]" class="form-select">
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
                        <input type="number" readonly id="parafuso_nivel_1" placeholder="Tam. Parafuso">
                    </td>
                    <td>
                        <select name="estruturas2[]" class="form-select">
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
                        <input type="number" readonly id="parafuso_nivel_2" placeholder="Tam. Parafuso">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger removeRow">Remover</button>
                    </td>
                `;
            tableBody.appendChild(newRow);

            // Evento para remover a linha
            newRow.querySelector('.removeRow').addEventListener('click', function() {
                tableBody.removeChild(newRow);
            });

            // Adiciona evento para o select do poste na nova linha
            newRow.querySelector('.posteSelect').addEventListener('change', function() {
                const posteId = this.value; // Obtém o ID do poste
                const row = this.closest('tr'); // Obtém a linha onde o select está

                if (posteId) {
                    const data = new URLSearchParams({
                        id: posteId,
                        action: 'buscarPostes' // Adiciona a ação
                    });

                    fetch('seu_script_aqui.php', { // Substitua pela URL do seu script
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: data.toString()
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.postes.length > 0) {
                                row.querySelector('#parafuso_nivel_1').value = data.postes[0].parafuso_nivel_1 || '';
                                row.querySelector('#parafuso_nivel_2').value = data.postes[0].parafuso_nivel_2 || '';
                            } else {
                                row.querySelector('#parafuso_nivel_1').value = '';
                                row.querySelector('#parafuso_nivel_2').value = '';
                            }
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        });
                } else {
                    row.querySelector('#parafuso_nivel_1').value = '';
                    row.querySelector('#parafuso_nivel_2').value = '';
                }
            });
        });
    });
    document.getElementById('postForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Previne o envio padrão do formulário

        var formData = new FormData(this); // Cria um objeto FormData

        // Converte FormData em uma string de consulta
        var dados = new URLSearchParams(formData).toString();

        console.log(dados); // Para verificar os dados serializados

        // Aqui você pode enviar os dados via AJAX, por exemplo:
        fetch('orcamento_controller', { // Altere para a sua URL
                method: 'POST',
                body: dados,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded' // Define o cabeçalho apropriado
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Manipule a resposta aqui
            })
            .catch(error => {
                console.error(error); // Manipule o erro aqui
            });
    });
    document.getElementById('posteSelect').addEventListener('change', function() {
        var posteId = this.value; // Obtém o valor selecionado

        if (posteId) {
            // Prepara os dados para enviar via POST
            var data = new URLSearchParams({
                id: posteId,
                action: 'buscarPostes' // Adiciona a ação
            });

            fetch('orcamento_controller', { // Altere para a sua URL de destino
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: data.toString() // Envia os dados
                })
                .then(response => {

                    return response.json(); // Espera uma resposta em JSON
                })
                .then(data => {
                    if (data.postes.length > 0) {
                        // Preenche os campos com os valores recebidos
                        document.getElementById('parafuso_nivel_1').value = data.postes[0].parafuso_nivel_1 || ''; // Se não houver valor, mantém vazio
                        document.getElementById('parafuso_nivel_2').value = data.postes[0].parafuso_nivel_2 || ''; // Se não houver valor, mantém vazio
                    } else {
                        // Limpa os campos se não houver dados
                        document.getElementById('parafuso_nivel_1').value = '';
                        document.getElementById('parafuso_nivel_2').value = '';
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        } else {
            // Limpa os campos se nenhuma opção estiver selecionada
            document.getElementById('parafuso_nivel_1').value = '';
            document.getElementById('parafuso_nivel_2').value = '';
        }
    });
    // document.getElementById('postForm').addEventListener('submit', function(event) {
    //     event.preventDefault();

    //     // Coletar os valores dos postes e estruturas
    //     const postes = Array.from(document.querySelectorAll('select[name="postes[]"]')).map(select => select.value);
    //     const estruturas1 = Array.from(document.querySelectorAll('select[name="estruturas1[]"]')).map(select => select.value);
    //     const estruturas2 = Array.from(document.querySelectorAll('select[name="estruturas2[]"]')).map(select => select.value);

    //     // Enviar os dados via AJAX para o script PHP
    //     fetch('orcamento_controller', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //             },
    //             body: JSON.stringify({
    //                 postes: postes,
    //                 estruturas1: estruturas1,
    //                 estruturas2: estruturas2
    //             })
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             const resultList = document.getElementById('resultList');
    //             resultList.innerHTML = ''; // Limpar resultados anteriores
    //             console.log(data);

    //             // Exibir resultados dos postes
    //             data.postes.forEach((poste, index) => {
    //                 resultList.innerHTML += `<li class="list-group-item">Poste ${index + 1}: ${poste.codigo}</li>`;
    //             });

    //             // Exibir materiais da estrutura 1
    //             data.estruturas1.forEach((estrutura1, index) => {
    //                 resultList.innerHTML += `<li class="list-group-item">Estrutura 1 - ${estrutura1.descricao_estrutura}:</li>`;
    //                 estrutura1.materiais.forEach(material => {
    //                     resultList.innerHTML += `<li class="list-group-item">${material}</li>`;
    //                 });
    //             });

    //             // Exibir materiais da estrutura 2
    //             data.estruturas2.forEach((estrutura2, index) => {
    //                 resultList.innerHTML += `<li class="list-group-item">Estrutura 2 - ${estrutura2.descricao_estrutura}:</li>`;
    //                 estrutura2.materiais.forEach(material => {
    //                     resultList.innerHTML += `<li class="list-group-item">${material}</li>`;
    //                 });
    //             });

    //             // Mostrar a seção de resultados
    //             document.getElementById('result').style.display = 'block';
    //         })

    //         .catch(error => console.error('Erro:', error));
    // });
</script>