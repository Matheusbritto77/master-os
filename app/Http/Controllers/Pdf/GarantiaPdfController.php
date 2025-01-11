<?php

namespace App\Http\Controllers\Pdf;

use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Models\Ordem;
use Dompdf\Options;
use App\Models\Garantia;
use App\Models\Orcamento;
use Illuminate\Support\Facades\Auth;
use App\Models\Logos;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image; // Importa a fachada do Intervention Image

class GarantiaPdfController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    

    

    /**
     * Gera o PDF da garantia específica ou da última criada pelo usuário autenticado.
     *
     * @param int|null $id
     * @return \Illuminate\Http\Response
     */
    public function gerarPDFGarantia($id = null)
    {
        $userId = Auth::id();

        // Se um ID foi fornecido, use-o para buscar a garantia do usuário; caso contrário, pegue a última garantia criada pelo usuário
        $garantia = $id ? Garantia::where('user_id', $userId)->find($id) : Garantia::where('user_id', $userId)->latest()->first();

        // Verificar se a garantia foi encontrada
        if (!$garantia) {
            return response()->json(['error' => 'Nenhuma garantia encontrada.']);
        }

        return $this->generatePdfFromGarantia($garantia, $userId);
    }

    /**
     * Gera o PDF da última garantia criada pelo usuário autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function gerarPDFUltimaGarantia()
    {
        $userId = Auth::id();

        // Buscar a última garantia criada pelo usuário
        $garantia = Garantia::where('user_id', $userId)->latest()->first();

        // Verificar se a garantia foi encontrada
        if (!$garantia) {
            return response()->json(['error' => 'Nenhuma garantia encontrada.']);
        }

        return $this->generatePdfFromGarantia($garantia, $userId);
    }

    /**
     * Método privado para gerar o PDF a partir de uma garantia.
     *
     * @param Garantia $garantia
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    // Continuação da função privada para gerar o PDF a partir de uma garantia
    private function generatePdfFromGarantia($garantia, $userId)
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

        // Carregar a visualização e passar os dados necessários
        $html = view('pdf_garantia', compact('garantia', 'imageBase64'))->render();

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
            'Content-Disposition' => 'inline; filename="garantia.pdf"',
        ]);
    }
}