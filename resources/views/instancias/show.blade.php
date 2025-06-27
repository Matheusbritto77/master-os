@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalhes da Instância: {{ $instancia->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Nome:</th>
                                    <td>{{ $instancia->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $instancia->isActive() ? 'success' : 'secondary' }}">
                                            {{ $instancia->isActive() ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Session ID:</th>
                                    <td>
                                        @if($instancia->session_id)
                                            <code>{{ $instancia->session_id }}</code>
                                        @else
                                            <span class="text-muted">Não definido</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Endpoint:</th>
                                    <td>
                                        <code>{{ $instancia->api_endpoint }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Descrição:</th>
                                    <td>
                                        @if($instancia->description)
                                            {{ $instancia->description }}
                                        @else
                                            <span class="text-muted">Nenhuma descrição</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Criado em:</th>
                                    <td>{{ $instancia->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Última atualização:</th>
                                    <td>{{ $instancia->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Ações da Instância</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($instancia->isActive())
                                            <button type="button" 
                                                    class="btn btn-danger stop-session" 
                                                    data-instancia-id="{{ $instancia->id }}">
                                                <i class="fas fa-stop"></i> Parar Sessão
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-success start-session" 
                                                    data-instancia-id="{{ $instancia->id }}">
                                                <i class="fas fa-play"></i> Iniciar Sessão
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                class="btn btn-info check-status" 
                                                data-instancia-id="{{ $instancia->id }}">
                                            <i class="fas fa-sync-alt"></i> Verificar Status
                                        </button>
                                        
                                        @if($instancia->isActive())
                                            <a href="{{ route('instancias.qr', $instancia) }}" 
                                               class="btn btn-warning">
                                                <i class="fas fa-qrcode"></i> Ver QR Code
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('instancias.edit', $instancia) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Editar Instância
                                        </a>
                                        
                                        <form action="{{ route('instancias.destroy', $instancia) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Tem certeza que deseja remover esta instância?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Remover Instância
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            @if($instancia->isActive())
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Status da Conexão</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <i class="fas fa-whatsapp fa-3x text-success mb-3"></i>
                                            <h5 class="text-success">Conectado</h5>
                                            <p class="text-muted">Sua instância está ativa e conectada ao WhatsApp</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title">Status da Conexão</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <i class="fas fa-whatsapp fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Desconectado</h5>
                                            <p class="text-muted">Sua instância está inativa. Inicie a sessão para conectar.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensagens -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensagem</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Iniciar sessão
    $('.start-session').click(function() {
        const instanciaId = $(this).data('instancia-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Iniciando...');
        
        $.ajax({
            url: `/instancias/${instanciaId}/start`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showMessage('Sessão iniciada com sucesso!', 'success');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Erro ao iniciar sessão';
                showMessage(error, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-play"></i> Iniciar Sessão');
            }
        });
    });

    // Parar sessão
    $('.stop-session').click(function() {
        const instanciaId = $(this).data('instancia-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Parando...');
        
        $.ajax({
            url: `/instancias/${instanciaId}/stop`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showMessage('Sessão encerrada com sucesso!', 'success');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Erro ao encerrar sessão';
                showMessage(error, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-stop"></i> Parar Sessão');
            }
        });
    });

    // Verificar status
    $('.check-status').click(function() {
        const instanciaId = $(this).data('instancia-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verificando...');
        
        $.ajax({
            url: `/instancias/${instanciaId}/status`,
            method: 'GET',
            success: function(response) {
                showMessage(`Status da sessão: ${response.status}`, 'info');
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Erro ao verificar status';
                showMessage(error, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Verificar Status');
            }
        });
    });

    function showMessage(message, type) {
        $('#modalMessage').text(message);
        $('#messageModal').modal('show');
    }
});
</script>
@endpush 