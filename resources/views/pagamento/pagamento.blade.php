<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escolha Seu Plano</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #1f2937;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #4b5563;
            width: 90%;
            max-width: 500px;
            color: #ffffff;
        }
        .close-modal {
            color: #ffffff;
        }
        .back-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            font-size: 1.25rem;
            color: #ffffff;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <header class="bg-gray-800 text-white p-4 relative">
        <span class="back-home cursor-pointer" onclick="window.location.href='/'">
            Voltar para Home
        </span>
        <div class="container mx-auto text-center mt-12">
            <h1 class="text-2xl sm:text-3xl font-bold">Escolha Seu Plano</h1>
            <p class="text-lg">Selecione um plano e confirme o pagamento para prosseguir usando o sistema e restabelesa seu acesso imediatamente:</p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap justify-center">
            <!-- Plano Mensal -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-xs w-full sm:w-72 mx-2 my-4">
                <div class="flex items-center mb-4">
                    <h2 class="text-xl font-bold ml-4">Plano Mensal</h2>
                </div>
                <p class="text-gray-400 mb-4">R$ 39,90 por mês</p>
                <button data-plan="mensal" data-amount="49.90" data-type="1" class="bg-blue-500 text-white px-4 py-2 rounded-lg open-modal">Assinar Agora</button>
            </div>

            <!-- Plano Semestral -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-xs w-full sm:w-72 mx-2 my-4">
                <div class="flex items-center mb-4">
                    <h2 class="text-xl font-bold ml-4">Plano Semestral</h2>
                </div>
                <p class="text-gray-400 mb-4">R$ 200,00 por 6 meses</p>
                <button data-plan="semestral" data-amount="200.00" data-type="2" class="bg-green-500 text-white px-4 py-2 rounded-lg open-modal">Assinar Agora</button>
            </div>

            <!-- Plano Anual -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-xs w-full sm:w-72 mx-2 my-4">
                <div class="flex items-center mb-4">
                    <h2 class="text-xl font-bold ml-4">Plano Anual</h2>
                </div>
                <p class="text-gray-400 mb-4">R$ 400,00 por 1 ano</p>
                <button data-plan="anual" data-amount="400.00" data-type="3" class="bg-red-500 text-white px-4 py-2 rounded-lg open-modal">Assinar Agora</button>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="payment-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal text-xl font-bold cursor-pointer">&times;</span>
            <h2 class="text-xl font-bold mb-4">Escolha o Método de Pagamento</h2>
            <div class="flex flex-col sm:flex-row justify-center">
                <button id="pix-payment" class="bg-green-500 text-white px-4 py-2 rounded-lg mb-2 sm:mb-0 sm:mr-2">Pix</button>
                <button id="credit-card-payment" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Cartão de Crédito</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.open-modal').forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('payment-modal');
                modal.style.display = 'block';

                // Armazenar os dados necessários diretamente do dataset
                const amount = this.dataset.amount;
                const type = this.dataset.type;
                
                document.getElementById('pix-payment').dataset.amount = amount;
                document.getElementById('pix-payment').dataset.type = type;
                document.getElementById('credit-card-payment').dataset.amount = amount;
                document.getElementById('credit-card-payment').dataset.type = type;
            });
        });

        document.querySelector('.close-modal').addEventListener('click', function() {
            document.getElementById('payment-modal').style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('payment-modal')) {
                document.getElementById('payment-modal').style.display = 'none';
            }
        });

        document.getElementById('pix-payment').addEventListener('click', function() {
            const amount = this.dataset.amount;
            const type = this.dataset.type;

            // Chama a rota com o type_payment = 'PIX'
            window.location.href = `assas/registrar-cliente/${type}/PIX`;
        });

        document.getElementById('credit-card-payment').addEventListener('click', function() {
            const amount = this.dataset.amount;
            const type = this.dataset.type;

            // Chama a rota com o type_payment = 'CREDIT_CARD'
            window.location.href = `assas/registrar-cliente/${type}/CREDIT_CARD`;
        });
    </script>
</body>
</html>
