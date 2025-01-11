<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemoteDatabaseController extends Controller
{
    public function listDatabases()
    {
        try {
            // Conexão com o banco remoto usando as variáveis do .env com sufixo _BRITTO
            $connection = [
                'driver' => env('DB_CONNECTION_BRITTO', 'mysql'),
                'host' => env('DB_HOST_BRITTO'),
                'port' => env('DB_PORT_BRITTO', 3306),
                'database' => env('DB_DATABASE_BRITTO'),
                'username' => env('DB_USERNAME_BRITTO'),
                'password' => env('DB_PASSWORD_BRITTO'),
            ];

            config(['database.connections.remote_britto' => $connection]);

            $databases = DB::connection('remote_britto')
                ->select('SHOW DATABASES');

            // Formata os resultados
            $formattedDatabases = array_map(fn($db) => $db->Database, $databases);

            return response()->json([
                'success' => true,
                'databases' => $formattedDatabases,
            ]);
        } catch (\Exception $e) {
            // Log de erro e resposta em caso de falha
            Log::error('Erro ao listar bancos de dados remotos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Não foi possível conectar ao banco de dados remoto.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
