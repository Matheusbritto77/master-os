@extends('layouts.bar')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">QR Code - {{ $instancia->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('instancias.index') }}">Instâncias</a></li>
                    <li class="breadcrumb-item active">QR Code</li>
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
                        <h3 class="card-title">Conectar WhatsApp - {{ $instancia->name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        @if(isset($data['qrcode']))
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle"></i> Instruções</h5>
                                <p class="mb-0">
                                    1. Abra o WhatsApp no seu celular<br>
                                    2. Vá em Configurações > Aparelhos conectados<br>
                                    3. Toque em "Conectar um aparelho"<br>
                                    4. Escaneie o QR Code abaixo
                                </p>
                            </div>

                            <div class="qr-container">
                                <img src="data:image/png;base64,{{ $data['qrcode'] }}" 
                                     alt="QR Code WhatsApp" 
                                     class="img-fluid"
                                     style="max-width: 300px; border: 1px solid #ddd; border-radius: 8px;">
                            </div>

                            <div class="mt-4">
                                <p class="text-muted">
                                    <strong>Status:</strong> 
                                    <span class="badge badge-warning">Aguardando conexão</span>
                                </p>
                                <p class="text-muted">
                                    <strong>Session ID:</strong> {{ $instancia->session_id }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="btn btn-primary check-connection" data-instancia-id="{{ $instancia->id }}">
                                    <i class="bi bi-arrow-clockwise"></i> Verificar Conexão
                                </button>
                                <button type="button" class="btn btn-secondary refresh-qr" data-instancia-id="{{ $instancia->id }}">
                                    <i class="bi bi-qr-code"></i> Atualizar QR Code
                                </button>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h5><i class="bi bi-exclamation-triangle"></i> Erro</h5>
                                <p class="mb-0">Não foi possível gerar o QR Code. Verifique se a sessão está ativa.</p>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('instancias.index') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left"></i> Voltar para Instâncias
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Verificar conexão
    $('.check-connection').click(function() {
        const instanciaId = $(this).data('instancia-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Verificando...');
        
        $.ajax({
            url: `/instancias/${instanciaId}/status`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'active') {
                    Swal.fire({
                        title: 'Conectado!',
                        text: 'WhatsApp conectado com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/instancias';
                    });
                } else {
                    Swal.fire({
                        title: 'Status',
                        text: `Status atual: ${response.status}`,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'Erro ao verificar conexão';
                Swal.fire({
                    title: 'Erro!',
                    text: error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i> Verificar Conexão');
            }
        });
    });

    // Atualizar QR Code
    $('.refresh-qr').click(function() {
        const instanciaId = $(this).data('instancia-id');
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Atualizando...');
        
        // Recarregar a página para obter novo QR Code
        setTimeout(() => {
            location.reload();
        }, 1000);
    });

    // Verificar conexão automaticamente a cada 5 segundos
    setInterval(function() {
        const instanciaId = $('.check-connection').data('instancia-id');
        
        $.ajax({
            url: `/instancias/${instanciaId}/status`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'active') {
                    Swal.fire({
                        title: 'Conectado!',
                        text: 'WhatsApp conectado com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/instancias';
                    });
                }
            }
        });
    }, 5000);
});
</script>
@endpush 