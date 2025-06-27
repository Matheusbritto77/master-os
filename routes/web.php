<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdensController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Pdf\GerarPdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\Ajustes\AjustesController;
use App\Http\Controllers\Ajustes\UpdateController; 
use App\Http\Controllers\Ajustes\EmpresaController; 
use App\Http\Controllers\Ajustes\LogoController; 
use App\Http\Controllers\Ajustes\ConfigController; 
use App\Http\Controllers\Pdf\GarantiaPdfController;
use App\Http\Controllers\Pdf\PdfOrcamentoControlher;
use App\Http\Controllers\Ajustes\OrdemController;  
use App\Http\Controllers\Ajustes\ControleController; 
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RelatoriosController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\GerencianetPixController;
use App\Http\Controllers\FacebookPixelController;
use App\Http\Controllers\AssasController;
use App\Http\Controllers\RemoteDatabaseController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Revendedor\RevendedorController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\InstanciaController;

use App\Http\Controllers\Ajustes\PayController;
























Route::get('/', function () {
    // Chama a função que envia o evento "ViewContent"
     app(FacebookPixelController::class)->sendViewContentEvent(request());
    // Renderiza a view 'welcome'
    return view('welcome');
});

Auth::routes(['verify' => true]);


Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Rota para exibir o modal de notificação geral
Route::get('/admin/modal-notificacao-geral', function () {
    return view('admin.modal_notificacao_geral');
})->name('admin.modal.notificacao-geral');

// Rota para enviar notificação
Route::post('/admin/enviar-notificacao', [UserController::class, 'enviarNotificacaoGeral'])
    ->name('admin.enviar.notificacao');

// Rota para buscar usuários por ID ou nome
Route::get('/admin/buscar-usuarios', [UserController::class, 'buscarUsuarios'])
    ->name('admin.buscar.usuarios');


// Rota para o controlador de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas para o controlador de verificação de e-mail
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('/test-openai', [OpenAIController::class, 'testConnection']);


// Rota para mostrar o formulário de cadastro
Route::get('/revendedores/create', [RevendedorController::class, 'create'])->name('revendedores.create');

// Rota para armazenar o revendedor
Route::post('/revendedores', [RevendedorController::class, 'registrar'])->name('revendedores.store');



















Route::get('/home', [HomeController::class, 'index'])->middleware('verified');
Route::get('/clientes', [ClienteController::class, 'indexx'])->name('clientes.index');
Route::get('/clientes/adicionar', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
Route::post('/ordens', [OrdensController::class, 'store'])->name('ordens.store');
Route::get('/adicionar-ordem', [OrdensController::class, 'create'])->name('adicionar_ordem');
Route::post('/adicionar-ordem', [OrdensController::class, 'store'])->name('adicionarordem');
Route::get('/buscar-cliente', [OrdensController::class, 'buscarCliente'])->name('buscar_cliente');
Route::get('/clientes/{id}', [OrdensController::class, 'show'])->name('clientes.show');
Route::post('/adicionar-tecnico', [FuncionarioController::class, 'store'])->name('adicionar_tecnico');
Route::any('/registrar-atendente', [FuncionarioController::class, 'create'])->name('registrar-atendente');
Route::delete('/remover-atendente/{id}', [FuncionarioController::class, 'destroy'])->name('remover-atendente');
Route::get('/atendentes', [FuncionarioController::class, 'getAtendentes']);
Route::delete('/remover-tecnico/{id}', [FuncionarioController::class, 'destroyTecnico'])->name('remover-tecnico');
Route::get('/tecnicos', [FuncionarioController::class, 'getTecnicos'])->name('tecnicos');


Route::any('/ajustes', [ConfigController::class, 'ajustes'])->name('ajustes');


Route::any('/preview/{model}', [OrdemController::class, 'previewModel']);



Route::get('/get-tecnicos', [FuncionarioController::class, 'getTecnicos'])->name('tecnicos');
Route::get('/get-atendentes', [FuncionarioController::class, 'getAtendentes']);
Route::get('/pdf', [GerarPdfController::class, 'gerarPDF'])->name('pdf');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/pdf/{id}', [GerarPdfController::class, 'gerarPDF'])->name('pdf');
Route::get('/ordens', [OrdensController::class, 'index'])->name('ordens.index');
Route::get('/gerarPDFUltima', [GerarPdfController::class, 'gerarPDFUltima'])->name('gerarPDFUltima');


Route::post('/garantias', [GarantiaController::class, 'store'])->name('garantias.store');

Route::any('/garantias/create', [GarantiaController::class, 'store'])->name('garantias.create');
Route::get('/gerar-pdf-ultima-garantia', [GarantiaPdfController::class, 'gerarPDFUltimaGarantia'])->name('gerar_pdf_ultima_garantia');
Route::get('/historico_garantias', [GarantiaController::class, 'historicoGarantias'])->name('historico_garantias');
Route::get('gerar-pdf-garantia/{id}', [GarantiaPdfController::class, 'gerarPDFGarantia'])->name('gerar_pdf_garantia');
Route::get('/busca_garantias', [GarantiaController::class, 'search'])->name('busca_garantias');
Route::get('/adicionar-Orcamento', [OrcamentoController::class, 'orcamento'])->name('adicionar_orcamento');
Route::any('/adicionarOrcamento', [OrcamentoController::class, 'createorcamento'])->name('adicionarorcamento');



Route::get('/gerar-pdf-ultimo-orcamento', [PdfOrcamentoControlher::class, 'gerarPDFUltimoOrcamento'])->name('gerarPDFUltimoOrcamento');
    
Route::get('/orcamentos', [OrcamentoController::class, 'index'])->name('orcamentos.index');
Route::get('/orcamentos/{id}', [PdfOrcamentoControlher::class, 'gerarPDFOrcamento'])->name('orcamentos.gerar-pdf');




// Rota para exibir o formulário de esqueci minha senha
Route::get('/forgot-password', [ResetPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Rota para enviar o email de reset de senha
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Rota para exibir o formulário de reset de senha
Route::get('reset-password/{token}/{email}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Rota para processar o reset de senha
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');



Route::get('/iniciar-caixa', [CaixaController::class, 'iniciarCaixa'])->name('caixa.iniciar');



// Rota para salvar o valor inicial do caixa
Route::post('/caixa/salvar-valor-inicial', [CaixaController::class, 'salvarValorInicial'])->name('caixa.salvar-valor-inicial');
Route::get('/caixas', [CaixaController::class, 'getCaixas'])->name('caixas.get');
Route::post('/registrar-venda', [CaixaController::class, 'registrarVenda'])->name('registrar.venda');
Route::post('/registrar-saida', [CaixaController::class, 'registrarSaida'])->name('registrar.saida');

Route::post('/registrar-despesa-fixa', [CaixaController::class, 'registrar'])->name('registrar.despesa');
Route::post('/registrar-valor-total-dia', [CaixaController::class, 'registrarValorTotalDia'])->name('registrar.valor.total.dia');


Route::any('/historico-caixa', [CaixaController::class, 'historicoCaixa'])->name('historico.caixa');



Route::post('/estoque', [EstoqueController::class, 'store'])->name('estoque.create');
Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');

// Rota para atualizar um produto específico
Route::put('/estoque/{produto}', [EstoqueController::class, 'update'])->name('estoque.update');

// Rota para excluir um produto específico
Route::delete('/delete/{produto}', [EstoqueController::class, 'destroy'])->name('estoque.destroy');

Route::get('/usuario/verificar-notificacoes', [UserController::class, 'verificarNotificacoes'])->name('usuario.verificar.notificacoes');




Route::post('estoque/baixa', [EstoqueController::class, 'darBaixa'])->name('estoque.baixa');

Route::post('/atualizar-modelo-ordem', [AjustesController::class, 'atualizarOuCriarModeloOrdem'])->name('atualizar.modelo.ordem');


Route::post('estoque/entrada', [EstoqueController::class, 'darEntrada'])->name('estoque.entrada');

Route::get('estoque/todos', [EstoqueController::class, 'todos'])->name('estoque.todos');
Route::get('caixa/status', [CaixaController::class, 'getCaixaStatus'])->name('caixa.status');











Route::get('/relatorios', [RelatoriosController::class, 'index'])->name('relatorios');
Route::post('/relatorios/data', [RelatoriosController::class, 'getReportData'])->name('relatorios.getReportData');
Route::post('/relatorios/get-profit-report-data', [RelatoriosController::class, 'getProfitReportData'])->name('relatorios.getProfitReportData');
Route::get('/lucro', [RelatoriosController::class, 'lucro'])->name('lucro.relatorios');
// Rota para verificar a necessidade de senha e validar a senha
// Rotas relacionadas ao relatório de lucro
Route::post('/processar-relatorio-lucro', [RelatoriosController::class, 'processarRelatorioLucro'])
    ->name('processar.relatorio.lucro');

Route::post('/verificar-senha-relatorio-lucro', [RelatoriosController::class, 'verificarSenhaRelatorioLucro'])
    ->name('verificar.senha.relatorio.lucro');

Route::get('/relatorio-lucro', [RelatoriosController::class, 'processarRelatorioLucro'])
    ->name('relatorio.lucro');

// Rotas relacionadas ao relatório bruto
Route::post('/verificar-senha-relatorio-bruto', [RelatoriosController::class, 'verificarSenhaRelatorioBruto'])
    ->name('verificar.senha.relatorio.bruto');

Route::get('/relatorio-bruto', [RelatoriosController::class, 'processarRelatorioBruto'])
    ->name('relatorio.bruto.index');

// Rotas relacionadas ao caixa
Route::post('caixas/{id}/verificar-senha', [CaixaController::class, 'verificarSenha'])
    ->name('caixas.verificarSenha');
// Rota para exibir o formulário de registro da empresa
Route::get('/empresa/registrar', [EmpresaController::class, 'create'])->name('empresa.create');

// Rota para processar o envio do formulário de registro da empresa
Route::post('/empresa/registrar', [EmpresaController::class, 'store'])->name('empresa.store');

// Rota para exibir o formulário de controle de modelos
Route::get('/modelo/ordem', [ModeloOrdemController::class, 'index'])->name('modelo.ordem');

// Rota para processar o envio do formulário de controle de modelos
Route::post('/modelo/atualizar', [OrdemController::class, 'atualizarOuCriarModeloOrdem'])->name('atualizar.modelo.ordem');


Route::get('/configuracoes', [AjustesController::class, 'showSettings'])->name('config.show');
Route::put('/configuracoes/{id}', [UpdateController::class, 'update'])->name('config.update');
Route::post('/configuracoes/logo', [LogoController::class, 'updateLogo'])->name('config.updateLogo');

Route::get('/config-cliente', [UpdateController::class, 'showConfigCliente'])->name('config.cliente');
Route::get('/config-logo', [LogoController::class, 'showLogoForm'])->name('config.logo');
 Route::get('/config-controle', [ControleController::class, 'showCocontrole'])->name('config.controle');
 Route::any('/config/modelo', [OrdemController::class, 'showOrdemModelo']);


Route::post('/register-controle', [ControleController::class, 'registerControle'])->name('register.controle');
Route::get('/config-reset', [ResetPasswordController::class, 'showReset'])->name('config.reset');
Route::get('/config-empresa', [EmpresaController::class, 'index'])->name('config.empresa');




Route::get('/verificar-assinatura/{user}', [AssinaturaController::class, 'verificarAssinatura'])
    ->name('assinatura.verificar');

    Route::get('/verificar-assinaturas', function () {
        $users = \App\Models\User::all();
        $controller = new \App\Http\Controllers\AssinaturaController();
        
        foreach ($users as $user) {
            $controller->verificarAssinatura($user);
        }
        
        return 'Verificação de assinaturas concluída';
    })->name('assinaturas.verificar-todos');
    
// web.php
Route::get('/pagina-de-pagamento', [PagamentoController::class, 'index'])->name('pagina.de.pagamento');
Route::get('/pagina-de-teste', [TesteController::class, 'index'])->name('pagina.de.teste');


Route::get('/generate-pix/{amount}/{type}', [GerencianetPixController::class, 'generateQRCode'])->name('generate.pix');


Route::get('/d', [GerencianetPixController::class, 'consultarPixRecebidosUltimos30Minutos'])->name('pix');
Route::get('/verificar-assinaturas-expiradas', [AssinaturaController::class, 'verificarAssinaturasExpiradas']);
Route::get('/estoque/baixo', [EstoqueController::class, 'mostrarEstoqueBaixo'])
    ->middleware(['auth', 'verified']);



Route::get('/ajustes/pay', [PayController::class, 'verificarAssinatura'])->name('ajustes.pay');
// Em routes/web.php
Route::post('/assinatura/renovar', [PagamentoController::class, 'index'])->name('assinatura.renovar');
Route::post('/processar-pagamento-cartao', [GerencianetPixController::class, 'processarPagamentoCartao'])
    ->name('pagamento.cartao');



// Rota para criar a sessão de Checkout
Route::any('/create-checkout-session', [StripeController::class, 'createCheckoutSession'])->name('stripe.createCheckoutSession');
Route::get('/payment-status/{paymentIntentId}', [StripeController::class, 'checkPaymentStatus']);
Route::get('/checkout-session-status/{sessionId}', [StripeController::class, 'checkCheckoutSessionStatus']);


Route::get('/update-subscriptions', [StripeController::class, 'updateSubscriptions'])
    ->name('updateSubscriptions');
    
    
    Route::get('/plano-contratado', function () {
    return view('plano-contratado'); // Nome da view sem a extensão .blade.php
});


Route::any('/assas/registrar-cliente/{type}/{type_payment}', [AssasController::class, 'registrarClienteAssas'])->name('assas.registrar-cliente');


Route::get('/remote-databases', [RemoteDatabaseController::class, 'listDatabases'])
    ->name('remote-databases.list');

// Rotas para o módulo de Schedules
Route::get('/schedules', [ScheduleController::class, 'list'])->name('schedules.list');
Route::get('/schedules/check', [ScheduleController::class, 'processScheduledPosts'])->name('schedules.process');
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
Route::patch('/schedules/{schedule}/toggle-status', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');

// Rota alternativa para processar agendamentos (sem autenticação para cron jobs)
Route::get('/process-schedules', function() {
    Log::info('Rota /process-schedules foi chamada');
    return response()->json([
        'success' => true,
        'message' => 'Processamento de agendamentos iniciado',
        'timestamp' => now()->toISOString(),
        'status' => 'running'
    ]);
})->name('process.schedules.alternative');

// Rotas para o módulo de Instâncias do WhatsApp
Route::get('/instancias', [InstanciaController::class, 'index'])->name('instancias.index');
Route::get('/instancias/create', [InstanciaController::class, 'create'])->name('instancias.create');
Route::post('/instancias', [InstanciaController::class, 'store'])->name('instancias.store');
Route::post('/instancias/create', [InstanciaController::class, 'createInstance'])->name('instancias.createInstance');
Route::get('/instancias/{instancia}', [InstanciaController::class, 'show'])->name('instancias.show');
Route::get('/instancias/{instancia}/edit', [InstanciaController::class, 'edit'])->name('instancias.edit');
Route::put('/instancias/{instancia}', [InstanciaController::class, 'update'])->name('instancias.update');
Route::delete('/instancias/{id}', [InstanciaController::class, 'destroy'])->name('instancias.destroy');

// Rotas para gerenciamento de sessões
Route::post('/instancias/{id}/start', [InstanciaController::class, 'startSession'])->name('instancias.start');
Route::post('/instancias/{id}/stop', [InstanciaController::class, 'stopSession'])->name('instancias.stop');
Route::post('/instancias/{id}/restart', [InstanciaController::class, 'restartSession'])->name('instancias.restart');
Route::get('/instancias/{id}/status', [InstanciaController::class, 'checkStatus'])->name('instancias.status');
Route::get('/instancias/{id}/qr', [InstanciaController::class, 'generateQRCode'])->name('instancias.qr');

// Rotas para gerenciamento global de sessões
Route::get('/instancias/sessions/list', [InstanciaController::class, 'listSessions'])->name('instancias.sessions.list');
Route::post('/instancias/sessions/terminate-inactive', [InstanciaController::class, 'terminateInactiveSessions'])->name('instancias.sessions.terminate-inactive');

Route::get('/agendamentos', [ScheduleController::class, 'index'])->name('agendamentos.index');
Route::get('/groups', [ScheduleController::class, 'groups'])->name('schedules.groups');



