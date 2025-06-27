<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Master-OS | Unfixed Sidebar">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="dist/css/adminlte.css"><!--end::Required Plugin(AdminLTE)-->
    <!-- Adicionando o favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
       /* Mantém os títulos, descrições e legendas brancas fora dos modais */
h1, h2, label, p, span, .field-description, legend {
    color: #ffffff; /* Branco */
}

/* Labels, descrições e legendas dentro dos modais não serão brancas */
.modal label, 
.modal .field-description, 
.modal p, 
.modal span, 
.modal legend {
    color: #000000; /* Preto ou outra cor desejada */
}
/* Labels dentro do formulário específico com classe form-filtro permanecem pretos */
.form-filtro label {
    color: #000000; /* Preto */
}

       /* Adicione uma sombra ao redor da sidebar */
.app-sidebar {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
    border-radius: 8px; /* Adiciona bordas arredondadas, se desejado */
    transition: box-shadow 0.3s ease-in-out; /* Transição suave ao aplicar sombra */
    
}

/* Ajuste a sombra para o cabeçalho da sidebar */
.sidebar-brand {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

/* Ajuste a sombra das imagens */
.brand-image {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Outras regras do estilo */
@media (max-width: 768px) {
    body {
        padding-top: 56px; /* Ajuste o valor conforme necessário */
    }

    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
        background-color: #3a4d68; /* Cor de fundo da navbar */
        
    }

    .navbar-nav {
        flex-direction: column;
        width: 100%;
    }

    .navbar-nav .nav-item {
        text-align: center;
    }

    .user-menu .dropdown-menu {
        width: 100%;
        text-align: center;
        background-color: #3a4d68; /* Cor de fundo do dropdown */
    }
}

.user-image {
    max-width: 40px;
    background-color: #314158; /* Cor de fundo da imagem do usuário */
}

.sidebar-brand,
.sidebar-wrapper,
.app-header {
    background-color: #3a4d68; /* Cor escura para a sidebar e header */
    
}

.app-wrapper,
.app-sidebar {
    background-color: #314158; /* Cor um pouco mais clara para o conteúdo */
}

.app-header a.nav-link,
.user-menu .dropdown-menu a {
    color: #ecf0f1; /* Texto claro para boa visibilidade */
    
}

.user-menu .user-header {
    background-color: #3a4d68; /* Cor de fundo da área do cabeçalho do usuário */
}

.user-menu .user-footer a {
    color: #ecf0f1; /* Texto claro para botões de rodapé do usuário */
}

.user-menu .dropdown-menu a.btn-default {
    background-color: #3498db; /* Cor dos botões */
    color: #ecf0f1; /* Texto claro nos botões */
}

.user-menu .dropdown-menu a.btn-default:hover {
    background-color: #2980b9; /* Cor de hover dos botões */
}

.app-footer {
    background-color: #3a4d68; /* Cor de fundo do rodapé */
    color: #ffffff; /* Branco */

border-top: 1px solid #808080; /* Borda cinza */
    box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.3); /* Sombra sutil */
    padding-top: 10px; /* Espaço entre a borda e o conteúdo */
    color: #ffffff; /* Cor branca para o texto */
}

.app-footer a {
    color: #ffffff; /* Branco para links */
}

.app-footer a:hover {
    color: #dddddd; /* Cor mais clara para links quando em hover */
}

    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="app-sidebar bg-gray-800 shadow-lg z-10">
            <!-- Conteúdo da sidebar aqui -->
        </aside>
        
        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-gray-900 text-white shadow-md">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="https://api.whatsapp.com/send?phone=5534999442627" class="nav-link" target="_blank">Dúvidas e Suporte Gratuito</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @php
                                $userId = Auth::id();
                                $userLogoPath = public_path('images/logo_' . $userId . '.jpg');
                                if (file_exists($userLogoPath)) {
                                    $logoSrc = asset('images/logo_' . $userId . '.jpg');
                                } else {
                                    $logoSrc = asset('images/logo_default.jpg');
                                }
                            @endphp
                            <img src="{{ $logoSrc }}" class="user-image rounded-full shadow-md" alt="User Image">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end bg-gray-800 text-white">
                            <li class="user-header bg-primary text-white">
                                <img id="logoPreview" src="{{ $logoSrc }}" alt="Logo do Sistema" class="max-w-xs mx-auto">
                                <p>
                                    {{ Auth::user()->name }} - Seja bem-vindo!
                                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                                </p>
                            </li>
                            <li class="user-footer bg-gray-800 text-white">
                                <a href="{{ route('ajustes') }}" class="btn btn-default btn-flat">Ajustes</a>
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="#" class="brand-link d-flex align-items-center">
            <img src="dist/assets/img/M.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">Master-Os</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Home</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Caixa -->
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>
                                    Caixa
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('caixa.iniciar') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Iniciar Caixa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('historico.caixa') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Histórico de Caixa</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Estoque -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>
                                    Estoque
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
    <li class="nav-item">
        <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#registerStockModal">
            <i class="nav-icon bi bi-circle"></i>
            <p>Registrar Estoque</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('estoque.index') }}" class="nav-link">
            <i class="nav-icon bi bi-circle"></i>
            <p>Ver Estoque</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#darBaixaModal">
            <i class="nav-icon bi bi-circle"></i>
            <p>Saida no Estoque</p>
        </a>
    </li>
    <li class="nav-item">
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#darBaixaModal">
        <i class="nav-icon bi bi-circle"></i>
        <p>Estoque Baixo lista</p>
    </a>
</li>

    <li class="nav-item">
        <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#darEntradaModal">
            <i class="nav-icon bi bi-circle"></i>
            <p>Entrada no Estoque</p>
        </a>
    </li>
</ul>
<!-- RELATÓRIOS -->
<li class="nav-item menu-open">
    <a href="#" class="nav-link active">
        <i class="nav-icon bi bi-bar-chart"></i> <!-- Ícone para Relatórios -->
        <p>
            Relatórios
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('relatorios') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Relatórios Brutos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('lucro.relatorios') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Relatórios de Lucro</p>
            </a>
        </li>
    </ul>
</li>


                        <!-- Funcionários -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>
                                    Funcionários
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalRegistrarAtendente">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Adicionar Atendente</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalAdicionarTecnico">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Adicionar Técnico</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalRemoverAtendente">




                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Remover Atendente</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalRemoverTecnico">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Remover Técnico</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Garantia -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-shield-check"></i>
                                <p>
                                    Garantia
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#modalCriarGarantia">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Criar Garantia</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('historico_garantias') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Histórico de Garantias</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Clientes -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-person-check"></i>
                                <p>
                                    Clientes
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#registerClientModal">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Registrar Cliente</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('clientes.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lista de Clientes</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Histórico de Ordem -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    Histórico de Ordem
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ordens.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lista de Ordens</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('orcamentos.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Lista de Orçamentos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Ordem -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-list-check"></i>
                                <p>
                                    Ordem
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('adicionar_orcamento') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Criar Orçamento</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('adicionar_ordem') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Criar Ordem</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- WhatsApp -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-whatsapp"></i>
                                <p>
                                    WhatsApp
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('instancias.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Instâncias</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://web.whatsapp.com" target="_blank" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>WhatsApp Web</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('agendamentos.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-calendar-event"></i>
                                        <p>Agendamentos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <!-- Conteúdo principal aqui -->
            @yield('content')
        </div>
    </div>

    <!-- Modais -->
    @include('modal.Reg_produto')
    @include('modal.Reg_atendente')
    @include('modal.Reg_tecnico')
    @include('modal.Rem_atendente')
    @include('modal.Rem_tecnico')
    @include('modal.Reg_cliente')
    @include('modal.Reg_garantia')
    @include('modal.baixa_estoque')
    @include('modal.ent_estoque')
    @include('modal.add_ordem')

    <!-- Modal Nova Instância WhatsApp -->
    <div class="modal fade" id="modalNovaInstancia" tabindex="-1" aria-labelledby="modalNovaInstanciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovaInstanciaLabel">
                        <i class="bi bi-whatsapp"></i> Nova Instância do WhatsApp
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formNovaInstancia" action="{{ route('instancias.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome da Instância <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="name" 
                                           name="name" 
                                           placeholder="Ex: Minha Instância WhatsApp"
                                           required>
                                    <div class="form-text">Nome único para identificar esta instância</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="api_endpoint" class="form-label">Endpoint da API <span class="text-danger">*</span></label>
                                    <input type="url" 
                                           class="form-control" 
                                           id="api_endpoint" 
                                           name="api_endpoint" 
                                           placeholder="https://api.whatsapp.com"
                                           required>
                                    <div class="form-text">URL da API do WhatsApp que você está usando</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Descrição opcional sobre esta instância"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Informações</h6>
                            <ul class="mb-0">
                                <li>Após criar a instância, você poderá iniciar uma sessão</li>
                                <li>Será gerado um QR Code para conectar o WhatsApp</li>
                                <li>Você pode gerenciar múltiplas instâncias simultaneamente</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Criar Instância
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div> <!--end::App Wrapper--> <!--begin::Script--> 
    <!--begin::jQuery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> <!--end::jQuery-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script> <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script> <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script> <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/pt-br.js"></script>
    <script>
        // Configurar moment.js para português brasileiro
        moment.locale('pt-br');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="dist/js/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script> 
    <script>
(function () {
    // Patch fetch
    const originalFetch = window.fetch;
    window.fetch = function (...args) {
        if (typeof args[0] === 'string' && args[0].startsWith('http://masteros.online')) {
            args[0] = args[0].replace('http://', 'https://');
        }
        return originalFetch.apply(this, args);
    };

    // Patch XMLHttpRequest
    const originalOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function (method, url, ...rest) {
        if (typeof url === 'string' && url.startsWith('http://masteros.online')) {
            url = url.replace('http://', 'https://');
        }
        return originalOpen.call(this, method, url, ...rest);
    };
})();
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('[src], [href]');
    elements.forEach(el => {
        if (el.src && el.src.startsWith('http://masteros.online')) {
            el.src = el.src.replace('http://', 'https://');
        }
        if (el.href && el.href.startsWith('http://masteros.online')) {
            el.href = el.href.replace('http://', 'https://');
        }
    });
});
</script>

<!-- Script para Modal Nova Instância -->
<script>
$(document).ready(function() {
    // Validação do formulário de nova instância
    $('#formNovaInstancia').submit(function(e) {
        const name = $('#name').val().trim();
        const endpoint = $('#api_endpoint').val().trim();
        
        if (!name) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe o nome da instância.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            $('#name').focus();
            return false;
        }
        
        if (!endpoint) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe o endpoint da API.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            $('#api_endpoint').focus();
            return false;
        }
        
        if (!isValidUrl(endpoint)) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe uma URL válida para o endpoint.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            $('#api_endpoint').focus();
            return false;
        }
        
        // Mostrar loading
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Criando...');
    });
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    // Reset do formulário quando o modal for fechado
    $('#modalNovaInstancia').on('hidden.bs.modal', function() {
        $('#formNovaInstancia')[0].reset();
        const submitBtn = $('#formNovaInstancia').find('button[type="submit"]');
        submitBtn.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Criar Instância');
    });
});
</script>

    
    
    <!--end::OverlayScrollbars Configure--> <!--end::Script-->
    
    @stack('scripts')
    
</body><!--end::Body-->
<footer class="app-footer"> <!--begin::To the end-->
            <div class="float-end d-none ">Otimo trabalho!</div> <!--end::To the end--> <!--begin::Copyright--> <strong>
                Copyright &copy; 2024-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">Master-Os</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer> <!--end::Footer-->

</html>
