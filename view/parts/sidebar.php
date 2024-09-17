<style>
    /* Estilo da Sidebar */
    .sidebar {
        height: 100vh;
        width: 250px;
        background-color: #343a40;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 60px;
        /* Para que o conteúdo da sidebar não sobreponha a navbar */
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        padding: 10px;
        display: block;
        font-size: 18px;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    /* Ajuste do layout para o conteúdo */
    .content {
        margin-left: 250px;
        padding: 20px;
        padding-top: 60px;
        /* Ajuste para compensar a altura da navbar */
    }

    /* Estilo da Navbar */
    .navbar {
        z-index: 1;
        /* Para que a navbar fique acima da sidebar */
    }

    /* Responsividade para telas pequenas */
    @media (max-width: 768px) {
        .sidebar {
            width: 100px;
        }

        .content {
            margin-left: 100px;
        }

        .sidebar a {
            font-size: 14px;
            padding: 8px;
        }
    }

    @media (max-width: 480px) {
        .sidebar {
            width: 0;
            display: none;
        }

        .content {
            margin-left: 0;
        }
    }
</style>

<!-- Sidebar -->
<div class="sidebar bg-dark">
    <a href="#home" class="text-white">Home</a>
    <a href="#about" class="text-white">Sobre</a>
    <a href="#services" class="text-white">Serviços</a>
    <a href="#contact" class="text-white">Contato</a>
</div>