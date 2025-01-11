<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plano Contratado com Sucesso</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .back-home {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            font-size: 1.25rem;
            color: #ffffff;
            cursor: pointer;
        }
        .icon {
            width: 4rem;
            height: 4rem;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">

    <header class="bg-gray-800 text-white p-4 relative text-center">
        <div class="container mx-auto mt-12">
            <div class="mb-8">
                <h1 class="text-3xl font-bold">Parabéns!</h1>
                <p class="text-lg mt-2">Você contratou com sucesso o plano escolhido.</p>
            </div>
            <div class="flex justify-center mb-6">
                <svg class="icon text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-1.6a6.4 6.4 0 110-12.8 6.4 6.4 0 010 12.8zm3.707-8.707a1 1 0 00-1.414 0L10 10.586l-2.293-2.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l4-4a1 1 0 000-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-lg mb-4">Aguarde um momento enquanto configuramos sua conta. Após a configuração, você poderá começar a usar o sistema.</p>
            <p class="text-lg">Clique no botão abaixo para voltar à página inicial.</p>
            <div class="mt-6">
                <button onclick="window.location.href='/'" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Voltar para Home</button>
            </div>
        </div>
    </header>
    
    
</body>
</html>
