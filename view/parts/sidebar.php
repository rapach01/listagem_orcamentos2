    <!-- <!DOCTYPE html>
<html lang="en">

<head>
    <title>Setup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="http://localhost/listagem_orcamentos2/assets/images/setup_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/listagem_orcamentos2/assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="http://localhost/listagem_orcamentos2/assets/js/jquery.min.js"></script>
    <script defer src="http://localhost/listagem_orcamentos2/assets/js/popper.js"></script>
    <script defer src="http://localhost/listagem_orcamentos2/assets/js/bootstrap.min.js"></script>
    <script defer src="http://localhost/listagem_orcamentos2/assets/js/main.js"></script>

</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar" class="active bg-secondary">
            <h1><a href="index.html" class="logo"></a></h1>
            <ul class="list-unstyled components mb-5">
                <li class="active">
                    <a href="http://localhost/listagem_orcamentos2/view/orcamento"><span class="fa fa-money-bills"></span>Gerar Orçamento</a>
                </li>
                <li>
                    <a href="http://localhost/listagem_orcamentos2/view/postes"><span class="fa fa-bolt"></span> Postes</a>
                </li>
                <li>
                    <a href="http://localhost/listagem_orcamentos2/view/estrutura"><span class="fa-solid fa-screwdriver-wrench"></span>Estruturas</a>
                    <i class="bi bi-lightbulb"></i>
                </li>
                <li>
                    <a href="http://localhost/listagem_orcamentos2/view/material"><span class="fa fa-cogs"></span>Materiais</a>
                </li>
            </ul>

            <div class="footer">
                <p>
                    Copyright &copy;<script>
                   
                    </script> Arthur Rapach<i class="icon-heart"
                        aria-hidden="true"></i> by <a href="https://github.com/rapach01" target="_blank"></a>
                </p>
            </div>
        </nav>

        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-secondary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>

                 
                </div>
</nav> -->
    <?php
        global $relativePath;
    ?>
    <!doctype html>
    <html lang="pt-br">

    <head>
        <title>Setup</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="http://localhost/listagem_orcamentos2/assets/images/setup_logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- JS -->
        <script defer src="http://localhost/listagem_orcamentos2/assets/js/jquery.min.js"></script>
        <script defer src="http://localhost/listagem_orcamentos2/assets/js/popper.js"></script>
        <script defer src="http://localhost/listagem_orcamentos2/assets/js/bootstrap.min.js"></script>
        <script defer src="http://localhost/listagem_orcamentos2/assets/js/main.js"></script>
        <!-- Icons, Fonts E Bootstrap -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>

    <body>

        <div class="wrapper d-flex align-items-stretch">
            <nav id="sidebar">
                <div class="custom-menu">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                </div>
                <h1><a href="#" class="logo">Setup</a></h1>
                <ul class="list-unstyled components mb-5">
                    <li class="<?= ($relativePath === '/view/orcamento') ? 'active' : '' ?>">
                        <a href="orcamento">
                            <span class="fa fa-money-bills mr-2"></span> Gerar Orçamento
                        </a>
                    </li>
                    <li class="<?= ($relativePath === '/view/postes') ? 'active' : '' ?>">
                        <a href="postes">
                            <span class="fa fa-bolt mr-2"></span> Postes
                        </a>
                    </li>
                    <li class="<?= ($relativePath === '/view/estrutura') ? 'active' : '' ?>">
                        <a href="estrutura">
                            <span class="fa fa-screwdriver-wrench mr-2"></span> Estruturas
                        </a>
                    </li>
                    <li class="<?= ($relativePath === '/view/material') ? 'active' : '' ?>">
                        <a href="material">
                            <span class="fa fa-cogs mr-2"></span> Materiais
                        </a>
                    </li>
                </ul>

            </nav>

            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5">