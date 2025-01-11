<!DOCTYPE html>
<html lang="en">

<head>
<!-- Meta Pixel Code -->
    <!-- Meta Pixel Code para o evento Purchase -->
<script>
    !function(f,b,e,v,n,t,s) {
        if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');

    // Inicializa o Pixel com o ID do Pixel do Facebook a partir do .env
    fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}'); // Puxando o ID do Pixel do arquivo .env

    // Chamando a função do evento PageView
    fbq('track', 'PageView'); // Evento PageView
</script>
<noscript>
    <img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"
    />
</noscript>



  <script>

// Defina seu token de acesso e ID do pixel
const accessToken = 'EAASu3KcMZAg0BO7XJlsIKZCVVMHSUO81tItCithhfsBjeRAfSZBEoh4zShW5V3p3BIFMEic9Ya53M3ZBuxpTbNFOOJt0PHXn2UZAR9VLQkW62K2kapbPdrbZBK8bJBrg9NAnjPlmDLMFKlleHH6KOFc783ZAwijkEmHqw6KlOeeNqfVrgZCikeGwIqOKev5BDJZANWgZDZD';
const pixelId = '1078228973780980';

// Função para enviar o evento "Ver conteúdo" para o Facebook
async function sendViewContentEvent() {
    // Pegue as informações do cliente e da página
    const userAgent = navigator.userAgent;  // Agente de usuário do cliente
    const eventUrl = window.location.href;  // URL de origem do evento
    const eventTime = Math.floor(Date.now() / 1000);  // Hora do evento (em timestamp Unix)

    // Dados do evento
    const data = {
        data: [{
            event_name: 'ViewContent',  // Nome do evento
            event_time: eventTime,  // Hora do evento
            action_source: 'website',  // Fonte da ação
            event_source_url: eventUrl,  // URL de origem do evento
            user_data: {
                client_user_agent: userAgent  // Agente de usuário do cliente
            },
            custom_data: {
                currency: 'USD',
                value: 100.00
            }
        }]
    };

    // Definir a URL da API de Conversões do Facebook
    const url = `https://graph.facebook.com/v11.0/${pixelId}/events?access_token=${accessToken}`;

    // Enviar a requisição com a Fetch API
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (response.ok) {
            console.log('Evento enviado com sucesso:', result);
        } else {
            console.error('Erro ao enviar o evento:', result);
        }
    } catch (error) {
        console.error('Erro de rede:', error);
    }
}

// Enviar evento quando a página for aberta ou vista
window.onload = sendViewContentEvent;
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master-OS</title>

    <!-- Meta Tags -->
    <meta name="title" content="Master-Os | Sidebar">
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
            padding-top: 60px; /* Adjust padding for topbar height */
            background-color: #314158;
            color: #ffffff;
        }

        .footer {
            background-color: #ffffff;
            color: #333;
        }
        .content-wrapper {
            background-color: #314158; /* Atualiza a cor de fundo */
        } 
        
        .sidebar-dark-primary {
            background-color: #3a4d68; /* Atualiza a cor de fundo da sidebar */
        }
    </style>
</head>
<!-- Adicionando o favicon -->
<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<body class="bg-[#3a4d68] text-gray-200 sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-[#3a4d68] navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="bi bi-list text-gray-200"></i>
                    </a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Add other topbar elements here -->
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('dist/assets/img/M.png') }}" alt="Master-OS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Master-OS</span>
    </a>
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Login -->
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="nav-icon bi bi-person"></i>
                                <p>Login</p>
                            </a>
                        </li>

                        <!-- Registrar -->
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">
                                <i class="nav-icon bi bi-person-plus"></i>
                                <p>Registrar</p>
                            </a>
                        </li>

                       <!-- Falar com a gente -->
<li class="nav-item">
    <a href="https://wa.me/5534999442627" class="nav-link" target="_blank">
        <i class="nav-icon bi bi-chat"></i>
        <p>Falar com a gente</p>
    </a>
</li>


                        <!-- Nosso Instagram -->
<li class="nav-item">
    <a href="https://www.instagram.com/os_master.sistema/" class="nav-link" target="_blank">
        <i class="nav-icon bi bi-instagram"></i>
        <p>Nosso Instagram</p>
    </a>
</li>
<li class="nav-item">
    <a href="https://os-master.online/dawnload/Master-Os.exe" class="nav-link" download>
        <i class="nav-icon bi bi-download"></i>
        <p>Download</p>
    </a>
</li>


                        
                        

                        <!-- Sobre a gente -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-info-circle"></i>
                                <p>Sobre a gente</p>
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
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

       <!-- Footer -->
<footer class="bg-[#3a4d68] text-gray-200 py-4">
    <div class="container mx-auto text-center">
        
        <strong class="text-gray-100">
            Copyright &copy; 2024-2024 <a href="https://adminlte.io" class="text-blue-300 hover:underline">Master-Os</a>.
        </strong>
        <span class="text-gray-300">Todos os direitos reservados.</span>
    </div>
</footer>
<!-- ./wrapper -->

    <!-- ./wrapper -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
