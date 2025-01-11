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

class PdfOrcamentoControlher extends Controller
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
     * Gera o PDF do orçamento específico ou do último criado pelo usuário autenticado.
     *
     * @param int|null $id
     * @return \Illuminate\Http\Response
     */
    public function gerarPDFOrcamento($id = null)
    {
        $userId = Auth::id();

        // Se um ID foi fornecido, use-o para buscar o orçamento do usuário; caso contrário, pegue o último orçamento criado pelo usuário
        $orcamento = $id ? Orcamento::where('user_id', $userId)->find($id) : Orcamento::where('user_id', $userId)->latest()->first();

        // Verificar se o orçamento foi encontrado
        if (!$orcamento) {
            return response()->json(['error' => 'Nenhum orçamento encontrado.']);
        }

        return $this->generatePdfFromOrcamento($orcamento, $userId);
    }

    /**
     * Gera o PDF do último orçamento criado pelo usuário autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function gerarPDFUltimoOrcamento()
    {
        $userId = Auth::id();

        // Buscar o último orçamento criado pelo usuário
        $orcamento = Orcamento::where('user_id', $userId)->latest()->first();

        // Verificar se o orçamento foi encontrado
        if (!$orcamento) {
            return response()->json(['error' => 'Nenhum orçamento encontrado.']);
        }

        return $this->generatePdfFromOrcamento($orcamento, $userId);
    }
    /**
     * Método privado para gerar o PDF a partir de um orçamento.
     *
     * @param Orcamento $orcamento
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    private function generatePdfFromOrcamento($orcamento, $userId)
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
        $html = view('pdf_orcamento', compact('orcamento', 'imageBase64'))->render();

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
            'Content-Disposition' => 'inline; filename="orcamento.pdf"',
        ]);
    }
}