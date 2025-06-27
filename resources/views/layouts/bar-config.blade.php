<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Master-OS</title>

    <!-- Meta Tags -->
    <meta name="title" content="Master-OS | Configurações">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, admin dashboard, charts, calendar, datepicker, tables, datatable, colorlibhq">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">

    <!-- Custom Styles -->
    <style>
        .main-content {
            flex: 1;
            padding-top: 60px;
            background-color: #314158;
            color: #ffffff;
        }

        .footer {
            background-color: #3a4d68;
            color: #333;
        }

        .content-wrapper {
            background-color: #314158;
        }

        .sidebar-dark-primary {
            background-color: #3a4d68;
        }

        .brand-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
        }

        .brand-link .brand-text {
            margin-left: 0.5rem;
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .brand-image {
            max-width: 100%;
            height: auto;
        }

        .navbar-nav .nav-item .nav-link img {
            width: 40px;
            height: auto;
        }

        .user-menu {
        position: relative;
        /* Ajuste o valor de acordo com a sua necessidade */
        margin-right: 15px; /* Adicione essa linha para mover o dropdown para a esquerda */
    }

    .user-menu .dropdown-menu {
        /* Ajuste o alinhamento do dropdown menu para a esquerda */
        left: -200px; /* Ajuste o valor para a sua necessidade */
    }
    </style>
</head>

<!-- Adicionando o favicon -->
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

<body class="bg-[#3a4d68] text-gray-200 sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-[#3a4d68] navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="bi bi-list text-gray-200"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('home') }}" class="nav-link text-gray-200">Home</a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="https://api.whatsapp.com/send?phone=5534999442627" class="nav-link text-gray-200" target="_blank">Dúvidas e Suporte Gratuito</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-widget="fullscreen">
                        <i class="bi bi-arrows-fullscreen text-gray-200"></i>
                        <i class="bi bi-fullscreen-exit text-gray-200" style="display: none;"></i>
                    </a>
                </li>
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        @php
                            $userId = Auth::id(); // Obtém o ID do usuário logado
                            $userLogoPath = public_path('images/logo_' . $userId . '.jpg'); // Caminho completo para a imagem da logo do usuário

                            if (file_exists($userLogoPath)) {
                                $logoSrc = asset('images/logo_' . $userId . '.jpg'); // Caminho para a logo do usuário
                            } else {
                                $logoSrc = asset('images/logo_default.jpg'); // Caso não exista, exibe a logo padrão
                            }
                        @endphp
                        <img src="{{ $logoSrc }}" class="user-image rounded-circle shadow" alt="User Image" style="max-width: 40px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img id="logoPreview" src="{{ $logoSrc }}" alt="Logo do Sistema" style="max-width: 200px;">
                            <p>
                                {{ Auth::user()->name }} - Seja bem-vindo!
                                <small>Membro desde {{ Auth::user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="{{ route('ajustes') }}" class="btn btn-default btn-flat">Ajustes</a>
                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="{{ asset('dist/assets/img/M.png') }}" alt="Master-OS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text">Master-OS</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('config.cliente') }}" class="nav-link">
                                <i class="nav-icon bi bi-person"></i>
                                <p>Configurações do Cliente</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('config.logo') }}" class="nav-link">
                                <i class="nav-icon bi bi-image"></i>
                                <p>Logo do Sistema</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('config.controle') }}" class="nav-link">
                                <i class="nav-icon bi bi-lock"></i>
                                <p>Controle de Acesso</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/config/modelo') }}" class="nav-link">
                                <i class="nav-icon bi bi-box"></i>
                                <p>Controle de Modelos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('config.empresa') }}" class="nav-link">
                                <i class="nav-icon bi bi-building"></i>
                                <p>Registro de Empresa</p>
                            </a>
                        </li>
                        <li class="nav-item">
    <a href="{{ route('config.reset') }}" class="nav-link">
        <i class="nav-icon bi bi-key"></i>
        <p>Redefinir Senha</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('ajustes.pay') }}" class="nav-link">
        <i class="nav-icon bi bi-credit-card"></i>
        <p>Gerenciar Assinatura</p>
    </a>
</li>


                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <main class="main-content p-4">
                        @yield('content')
                    </main>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="footer py-4">
            <div class="container mx-auto text-center">
                <strong class="text-gray-100">Copyright &copy; 2024-2024 <a href="https://adminlte.io" class="text-blue-300 hover:underline">Master-OS</a>.</strong>
                <span class="text-gray-300">Todos os direitos reservados.</span>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarToggle = document.querySelector('[data-widget="pushmenu"]');
            const fullscreenToggle = document.querySelector('[data-widget="fullscreen"]');
            const body = document.body;

            sidebarToggle.addEventListener("click", function() {
                body.classList.toggle("sidebar-open");
            });

            fullscreenToggle.addEventListener("click", function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            });
        });

       


    
    </script>
   

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
