<?php
// Caminho para o arquivo a ser baixado
$file = 'Master-Os.exe';

// Verifica se o arquivo existe
if (file_exists($file)) {
    // Exibe a mensagem de agradecimento primeiro
    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Obrigado pelo Download</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                margin: 50px;
            }
            h1 {
                color: #4CAF50; /* Cor do título */
            }
            p {
                font-size: 18px;
            }
        </style>
        <script>
            // Inicia o download após 2 segundos
            setTimeout(function() {
                window.location.href = "' . $file . '";
            }, 2000);
        </script>
    </head>
    <body>
        <h1>Obrigado pelo Download!</h1>
        <p>Seu arquivo está sendo baixado em breve. Verifique sua pasta de Downloads.</p>
    </body>
    </html>';
    exit;
} else {
    // Se o arquivo não existe, exibe uma mensagem de erro
    echo 'Arquivo não encontrado.';
}
?>
