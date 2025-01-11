<?php
// Caminho onde está armazenada a versão
$versionFile = __DIR__ . '/version.txt';

// Verifica se o arquivo de versão existe
if (file_exists($versionFile)) {
    $version = file_get_contents($versionFile);

    // Retorna a versão em formato JSON
    echo json_encode([
        'status' => 'success',
        'version' => trim($version),
    ]);
} else {
    // Retorna uma mensagem de erro caso o arquivo não seja encontrado
    echo json_encode([
        'status' => 'error',
        'message' => 'Version file not found.'
    ]);
}
?>
