@extends('layouts.bar')

@section('content')
<!-- Meta tag CSRF para requisições AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Instâncias do WhatsApp</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Instâncias</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gerenciar Instâncias do WhatsApp</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" id="btnCriarInstancia">
                                <i class="bi bi-plus-circle"></i> Criar Instância
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Loading inicial -->
                        <div id="loadingInstancias" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2">Carregando instâncias...</p>
                        </div>

                        <!-- Tabela de instâncias -->
                        <div id="tabelaInstancias" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Status</th>
                                            <th>Data de Criação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyInstancias">
                                        <!-- Instâncias serão carregadas aqui via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mensagem quando não há instâncias -->
                        <div id="semInstancias" class="text-center py-4" style="display: none;">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Nenhuma instância encontrada</h5>
                            <p class="text-muted">Clique em "Criar Instância" para começar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para QR Code -->
<div class="modal fade" id="modalQRCode" tabindex="-1" aria-labelledby="modalQRCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQRCodeLabel">QR Code para Conectar WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="loadingQR" class="py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Gerando QR Code...</span>
                    </div>
                    <p class="mt-2">Gerando QR Code... (pode demorar até 30 segundos)</p>
                </div>
                <div id="qrCodeContent" style="display: none;">
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                    <p class="mt-3 text-muted">Escaneie este QR Code com seu WhatsApp para conectar a instância.</p>
                    
                    <!-- Área para status de verificação -->
                    <div id="verificacaoStatus" class="mt-3">
                        <!-- Status será inserido aqui dinamicamente -->
                    </div>
                </div>
                <div id="qrCodeError" style="display: none;">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="text-danger mt-2" id="qrCodeErrorMessage"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Carregar instâncias automaticamente quando a página carregar
    carregarInstancias();

    // Botão criar instância
    $('#btnCriarInstancia').click(function() {
        criarInstancia();
    });

    // Função para carregar instâncias
    function carregarInstancias() {
        $('#loadingInstancias').show();
        $('#tabelaInstancias').hide();
        $('#semInstancias').hide();

        $.ajax({
            url: '{{ route("instancias.index") }}',
            method: 'GET',
            success: function(response) {
                $('#loadingInstancias').hide();
                
                if (response.instancias && response.instancias.length > 0) {
                    renderizarInstancias(response.instancias);
                    $('#tabelaInstancias').show();
                } else {
                    $('#semInstancias').show();
                }
            },
            error: function(xhr) {
                $('#loadingInstancias').hide();
                $('#semInstancias').show();
                
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao carregar instâncias. Tente novamente.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    // Função para renderizar instâncias na tabela
    function renderizarInstancias(instancias) {
        const tbody = $('#tbodyInstancias');
        tbody.empty();

        instancias.forEach(function(instancia) {
            const statusClass = instancia.status === 'active' ? 'success' : 'secondary';
            const statusText = instancia.status === 'active' ? 'Ativo' : 'Inativo';
            const statusIcon = instancia.status === 'active' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
            
            const row = `
                <tr>
                    <td>${instancia.name}</td>
                    <td>
                        <span class="badge bg-${statusClass}">
                            <i class="bi ${statusIcon} me-1"></i>${statusText}
                        </span>
                    </td>
                    <td>${new Date(instancia.created_at).toLocaleDateString('pt-BR')}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-success btn-checar-status" data-instancia-id="${instancia.id}" 
                                    title="Verifica manualmente se a instância está conectada ao WhatsApp">
                                <i class="bi bi-check-circle"></i> Checar Status
                            </button>
                            <button type="button" class="btn btn-sm btn-info btn-checar-qr" data-instancia-id="${instancia.id}">
                                <i class="bi bi-qr-code"></i> Checar QR
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-excluir-instancia" data-instancia-id="${instancia.id}">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Adicionar event listeners aos botões
        $('.btn-checar-status').click(function() {
            const instanciaId = $(this).data('instancia-id');
            checarStatus(instanciaId);
        });

        $('.btn-checar-qr').click(function() {
            const instanciaId = $(this).data('instancia-id');
            gerarQRCode(instanciaId);
        });

        $('.btn-excluir-instancia').click(function() {
            const instanciaId = $(this).data('instancia-id');
            excluirInstancia(instanciaId);
        });
    }

    // Função para criar instância
    function criarInstancia() {
        const button = $('#btnCriarInstancia');
        
        // Verificar se já existe uma instância
        if ($('#tbodyInstancias tr').length > 0) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Você já possui uma instância. Cada usuário pode ter apenas uma instância.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Criando...');

        $.ajax({
            url: '{{ route("instancias.createInstance") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        carregarInstancias();
                    });
                } else {
                    Swal.fire({
                        title: 'Atenção!',
                        text: response.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Erro ao criar instância. Tente novamente.';
                Swal.fire({
                    title: 'Erro!',
                    text: error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="bi bi-plus-circle"></i> Criar Instância');
            }
        });
    }

    // Função para gerar QR Code
    function gerarQRCode(instanciaId) {
        $('#modalQRCode').modal('show');
        $('#loadingQR').show();
        $('#qrCodeContent').hide();
        $('#qrCodeError').hide();

        $.ajax({
            url: `/instancias/${instanciaId}/qr`,
            method: 'GET',
            success: function(response) {
                $('#loadingQR').hide();
                
                if (response.success && response.qr_code) {
                    // Converte o QR code para uma imagem usando uma API de terceiros
                    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(response.qr_code)}`;
                    $('#qrCodeImage').attr('src', qrCodeUrl);
                    $('#qrCodeContent').show();
                    
                    // Inicia a verificação automática de conexão
                    iniciarVerificacaoConexao(instanciaId);
                } else {
                    $('#qrCodeErrorMessage').text(response.message || 'Erro ao gerar QR Code');
                    $('#qrCodeError').show();
                }
            },
            error: function(xhr) {
                $('#loadingQR').hide();
                const error = xhr.responseJSON?.message || 'Erro ao gerar QR Code. Tente novamente.';
                $('#qrCodeErrorMessage').text(error);
                $('#qrCodeError').show();
            }
        });
    }

    // Função para verificar conexão automaticamente
    let verificacaoInterval;
    let tentativasVerificacao = 0;
    const maxTentativas = 24; // 2 minutos (24 * 5 segundos)

    function iniciarVerificacaoConexao(instanciaId) {
        // Limpa qualquer verificação anterior
        if (verificacaoInterval) {
            clearInterval(verificacaoInterval);
        }
        
        tentativasVerificacao = 0;
        
        // Atualiza o indicador de verificação no modal
        $('#verificacaoStatus').html(`
            <div class="d-flex align-items-center justify-content-center">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                    <span class="visually-hidden">Verificando...</span>
                </div>
                <span class="text-muted">Verificando conexão...</span>
            </div>
        `);

        // Inicia verificação a cada 5 segundos
        verificacaoInterval = setInterval(function() {
            verificarStatusConexao(instanciaId);
        }, 5000);
    }

    function verificarStatusConexao(instanciaId) {
        tentativasVerificacao++;
        
        $.ajax({
            url: `/instancias/${instanciaId}/status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    if (response.status === 'active' || response.api_status === 'CONNECTED') {
                        // WhatsApp conectado com sucesso
                        clearInterval(verificacaoInterval);
                        
                        $('#verificacaoStatus').html(`
                            <div class="alert alert-success mt-3">
                                <i class="bi bi-check-circle-fill"></i>
                                <strong>WhatsApp conectado com sucesso!</strong>
                                <p class="mb-0 mt-2">A instância está pronta para uso.</p>
                            </div>
                        `);
                        
                        // Atualiza a lista de instâncias após 2 segundos
                        setTimeout(function() {
                            carregarInstancias();
                        }, 2000);
                        
                        return;
                    }
                }
                
                // Verifica se atingiu o limite de tentativas
                if (tentativasVerificacao >= maxTentativas) {
                    clearInterval(verificacaoInterval);
                    
                    $('#verificacaoStatus').html(`
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Tempo limite excedido</strong>
                            <p class="mb-0 mt-2">O QR Code expirou. Clique em "Checar QR" novamente para gerar um novo código.</p>
                        </div>
                    `);
                } else {
                    // Atualiza o contador de tentativas
                    const tempoRestante = Math.ceil((maxTentativas - tentativasVerificacao) * 5 / 60);
                    $('#verificacaoStatus').html(`
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">Verificando...</span>
                            </div>
                            <span class="text-muted">Verificando conexão... (${tempoRestante} min restantes)</span>
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                console.error('Erro ao verificar status:', xhr);
                
                // Em caso de erro, continua tentando até o limite
                if (tentativasVerificacao >= maxTentativas) {
                    clearInterval(verificacaoInterval);
                    
                    $('#verificacaoStatus').html(`
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Erro na verificação</strong>
                            <p class="mb-0 mt-2">Não foi possível verificar o status da conexão.</p>
                        </div>
                    `);
                }
            }
        });
    }

    // Limpa o intervalo quando o modal for fechado
    $('#modalQRCode').on('hidden.bs.modal', function() {
        if (verificacaoInterval) {
            clearInterval(verificacaoInterval);
            verificacaoInterval = null;
        }
        tentativasVerificacao = 0;
        $('#verificacaoStatus').empty();
    });

    // Função para excluir instância
    function excluirInstancia(instanciaId) {
        Swal.fire({
            title: 'Confirmar Exclusão',
            text: 'Deseja realmente excluir esta instância? Esta ação não pode ser desfeita.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/instancias/${instanciaId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                carregarInstancias();
                            });
                        } else {
                            Swal.fire({
                                title: 'Erro!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON?.message || 'Erro ao excluir instância. Tente novamente.';
                        Swal.fire({
                            title: 'Erro!',
                            text: error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    }

    // Função para checar status manualmente
    function checarStatus(instanciaId) {
        const button = $(`.btn-checar-status[data-instancia-id="${instanciaId}"]`);
        const originalText = button.html();
        
        // Mostra loading no botão
        button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Verificando...');

        $.ajax({
            url: `/instancias/${instanciaId}/status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let statusText = '';
                    let statusClass = '';
                    let icon = '';
                    let description = '';
                    
                    // Determina o texto, cor e descrição baseado no status
                    if (response.status === 'active' || response.api_status === 'CONNECTED') {
                        statusText = 'Conectado';
                        statusClass = 'success';
                        icon = 'bi-check-circle-fill';
                        description = 'Sua instância está conectada e pronta para uso.';
                    } else if (response.api_status === 'QR_READY' || response.api_status === 'QR_READY_RETRY') {
                        statusText = 'QR Code Disponível';
                        statusClass = 'info';
                        icon = 'bi-qr-code';
                        description = 'Escaneie o QR Code para conectar o WhatsApp.';
                    } else if (response.api_status === 'CONNECTING' || response.api_status === 'PAIRING') {
                        statusText = 'Conectando...';
                        statusClass = 'warning';
                        icon = 'bi-arrow-clockwise';
                        description = 'A instância está tentando conectar. Aguarde um momento.';
                    } else if (response.status === 'inactive' || response.api_status === 'DISCONNECTED') {
                        statusText = 'Desconectado';
                        statusClass = 'secondary';
                        icon = 'bi-x-circle-fill';
                        description = 'A instância não está conectada. Use "Checar QR" para conectar.';
                    } else {
                        statusText = 'Status Desconhecido';
                        statusClass = 'warning';
                        icon = 'bi-question-circle-fill';
                        description = 'Não foi possível determinar o status da conexão.';
                    }
                    
                    // Mostra o resultado com SweetAlert
                    Swal.fire({
                        title: 'Status da Instância',
                        html: `
                            <div class="text-center">
                                <i class="bi ${icon} text-${statusClass}" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-${statusClass}">${statusText}</h5>
                                <p class="text-muted mb-2">${description}</p>
                                <small class="text-muted">Status da API: ${response.api_status || 'N/A'}</small>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                    
                    // Atualiza a lista de instâncias para refletir o novo status
                    setTimeout(function() {
                        carregarInstancias();
                    }, 1000);
                    
                } else {
                    Swal.fire({
                        title: 'Erro!',
                        text: response.message || 'Erro ao verificar status.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Erro ao verificar status. Tente novamente.';
                Swal.fire({
                    title: 'Erro!',
                    text: error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                // Restaura o botão
                button.prop('disabled', false).html(originalText);
            }
        });
    }
});
</script>
@endpush 