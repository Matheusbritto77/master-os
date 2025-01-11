<?php

namespace App\Http\Controllers\Pdf;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Models\Ordem;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class GerarPdfController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function gerarPDF($id = null)
    {
        $userId = Auth::id();

        // Se um ID foi fornecido, use-o para buscar a ordem do usuário; caso contrário, pegue a última ordem criada pelo usuário
        $ordem = $id ? Ordem::where('user_id', $userId)->find($id) : Ordem::where('user_id', $userId)->latest()->first();

        // Verificar se a ordem foi encontrada
        if (!$ordem) {
            return response()->json(['error' => 'Nenhuma ordem de serviço encontrada.']);
        }

        // Obter dados da empresa associada ao usuário
        $empresa = Empresa::where('user_id', $userId)->first();

        return $this->generatePdfFromOrder($ordem, $userId, $empresa);
    }

    private function generatePdfFromOrder($ordem, $userId, $empresa)
    {
        // Verificar se existe o arquivo de logo específico para o usuário
        $imagePath = public_path("images/logo_{$userId}.jpg");
    
        // Se o arquivo de logo específico existir, carregar e converter em base64
        if (File::exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageBase64 = 'data:image/jpeg;base64,' . $imageData;
        } else {
            // Caso não exista, utilizar um logo padrão ou deixar sem logo
            $imageBase64 = null; // Pode ser definido um valor padrão aqui
        }
    
        // Obter o modelo de visualização do usuário logado
        $modelo = \App\Models\ModeloOrdem::where('user_id', $userId)
                    ->where(function ($query) {
                        $query->where('modelo_1', 1)
                              ->orWhere('modelo_2', 1)
                              ->orWhere('modelo_3', 1)
                              ->orWhere('modelo_4', 1);
                    })
                    ->first();
    
        // Determinar qual modelo usar
        if ($modelo) {
            if ($modelo->modelo_1 == 1) {
                $view = 'modelos.modelo_1';
            } elseif ($modelo->modelo_2 == 1) {
                $view = 'modelos.modelo_2';
            } elseif ($modelo->modelo_3 == 1) {
                $view = 'modelos.modelo_3';
            } elseif ($modelo->modelo_4 == 1) {
                $view = 'modelos.modelo_4';
            } else {
                $view = 'modelos.modelo_1'; // Padrão se nenhum modelo estiver ativo
            }
        } else {
            $view = 'modelos.modelo_1'; // Padrão se não houver registro para o usuário
        }
    
        // Carregar a visualização e passar os dados necessários
        $html = view($view, compact('ordem', 'imageBase64', 'empresa'))->render();
    
        // Configurar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permite carregar URLs externas
        $pdf = new Dompdf($options);
    
        // Carregar o HTML no Dompdf
        $pdf->loadHtml($html);
    
        // Definir o tamanho do papel e a orientação
        $pdf->setPaper('A4', 'portrait');
    
        // Renderizar o PDF
        $pdf->render();
    
        // Enviar o PDF gerado para o navegador
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ordem_de_servico.pdf"',
        ]);
    }

    public function gerarPDFUltima()
    {
        $userId = Auth::id();

        // Buscar a última ordem criada pelo usuário
        $ordem = Ordem::where('user_id', $userId)->latest()->first();

        // Verificar se a ordem foi encontrada
        if (!$ordem) {
            return response()->json(['error' => 'Nenhuma ordem de serviço encontrada.']);
        }

        // Obter dados da empresa associada ao usuário
        $empresa = Empresa::where('user_id', $userId)->first();

        return $this->generatePdfFromOrder($ordem, $userId, $empresa);
    }
}
