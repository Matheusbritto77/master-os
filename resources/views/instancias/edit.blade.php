@extends('layouts.bar')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Instância</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('instancias.index') }}">Instâncias</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                        <h3 class="card-title">Editar Instância: {{ $instancia->name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instancias.update', $instancia) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nome da Instância <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $instancia->name) }}" 
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
                                               value="{{ old('api_endpoint', $instancia->api_endpoint) }}" 
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
                                          placeholder="Descrição opcional sobre esta instância">{{ old('description', $instancia->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <h5><i class="bi bi-exclamation-triangle"></i> Atenção</h5>
                                <ul class="mb-0">
                                    <li>Alterar o endpoint da API pode afetar a conexão atual</li>
                                    <li>Se a instância estiver ativa, considere pará-la antes de fazer alterações</li>
                                    <li>Após editar, você pode precisar reiniciar a sessão</li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Atualizar Instância
                                </button>
                                <a href="{{ route('instancias.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
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
    // Validação do formulário
    $('form').submit(function(e) {
        const name = $('#name').val().trim();
        const endpoint = $('#api_endpoint').val().trim();
        
        if (!name) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe o nome da instância.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            $('#name').focus();
            return false;
        }
        
        if (!endpoint) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe o endpoint da API.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            $('#api_endpoint').focus();
            return false;
        }
        
        if (!isValidUrl(endpoint)) {
            e.preventDefault();
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, informe uma URL válida para o endpoint.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
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