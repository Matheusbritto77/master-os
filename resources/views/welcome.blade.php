@extends('layouts.guest')

@section('head')

<!-- End Meta Pixel Code -->
<!-- End Meta Pixel Code -->
    <!-- Google tag (gtag.js) -->
    <!-- Adicionando o favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0KP0TXR5K4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-0KP0TXR5K4');
        
    

    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master-Os</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-1m5iE9AkZWGO2fz78r4E6CjC02m5r1oGM2CgyjxATDb1Ft/R8T5OBkHm9s9a8IV6ZJ/jdTDKqYdQ+cYBlf6aFw==" crossorigin="anonymous" />
    <!-- CSS Externo para o Framework de Imagens -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picnic-css/dist/picnic.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
   
@endsection

@section('content')
    <main class="py-8 px-4 bg-gray-900 min-h-screen text-gray-200 flex flex-col items-center">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4 text-white">Seja bem-vindo ao Master-Os</h1>
            <p class="text-lg mb-8">Gestão da sua assistência técnica de forma eficiente e organizada.</p>

            <div class="features grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-cubes"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">Controle de Estoque</h2>
                    <p>Gerencie seu estoque de forma fácil e eficaz.</p>
                </div>
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-coins"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">Fluxo de Caixa</h2>
                    <p>Mantenha o controle financeiro da sua empresa atualizado.</p>
                </div>
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-shield-alt"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">Emissão de Garantia</h2>
                    <p>Emita garantias de forma simples e rápida para seus clientes.</p>
                </div>
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-wrench"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">Ordem de Serviço</h2>
                    <p>Crie ordens de serviço detalhadas para melhor atender seus clientes.</p>
                </div>
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">Orçamento</h2>
                    <p>Elabore orçamentos precisos para seus clientes com facilidade.</p>
                </div>
                <div class="feature bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="icon text-4xl mb-4 text-blue-400"><i class="fas fa-users"></i></div>
                    <h2 class="text-2xl font-semibold mb-2 text-white">CRM de Clientes</h2>
                    <p>Mantenha um relacionamento próximo com seus clientes.</p>
                </div>
            </div>
        </div>
    </main>
    

@endsection
