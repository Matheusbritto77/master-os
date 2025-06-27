@extends('layouts.bar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nova Instância do WhatsApp</h3>
                    <div class="card-tools">
                        <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('instancias.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome da Instância <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ex: Minha Instância WhatsApp"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Nome único para identificar esta instância
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="api_endpoint">Endpoint da API <span class="text-danger">*</span></label>
                                    <input type="url" 
                                           class="form-control @error('api_endpoint') is-invalid @enderror" 
                                           id="api_endpoint" 
                                           name="api_endpoint" 
                                           value="{{ old('api_endpoint') }}" 
                                           placeholder="https://api.whatsapp.com"
                                           required>
                                    @error('api_endpoint')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL da API do WhatsApp que você está usando
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Descrição opcional sobre esta instância">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Informações Importantes</h5>
                            <ul class="mb-0">
                                <li>Certifique-se de que o endpoint da API está correto e acessível</li>
                                <li>A instância será criada com status "Inativo" por padrão</li>
                                <li>Após criar a instância, você poderá iniciar a sessão e conectar o WhatsApp</li>
                                <li>Você precisará escanear um QR Code para conectar sua conta do WhatsApp</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Criar Instância
                            </button>
                            <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Validação do formulário
    $('form').submit(function(e) {
        const name = $('#name').val().trim();
        const endpoint = $('#api_endpoint').val().trim();
        
        if (!name) {
            e.preventDefault();
            alert('Por favor, informe o nome da instância.');
            $('#name').focus();
            return false;
        }
        
        if (!endpoint) {
            e.preventDefault();
            alert('Por favor, informe o endpoint da API.');
            $('#api_endpoint').focus();
            return false;
        }
        
        if (!isValidUrl(endpoint)) {
            e.preventDefault();
            alert('Por favor, informe uma URL válida para o endpoint.');
            $('#api_endpoint').focus();
            return false;
        }
    });
    
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
});
</script>
@endpush 