@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="card border-dark bg-dark text-light">
        <div class="card-header bg-secondary">Controle de Modelos</div>
        <div class="card-body">
            <!-- Formulário de Controle de Modelos -->
            <form id="modeloForm" method="POST" action="{{ route('atualizar.modelo.ordem') }}">
                @csrf
                <!-- Campos ocultos para garantir que campos não marcados enviem 0 -->
                <input type="hidden" name="modelo_1" value="0">
                <input type="hidden" name="modelo_2" value="0">
                <input type="hidden" name="modelo_3" value="0">
                <input type="hidden" name="modelo_4" value="0">

                <div class="row">
                    <!-- Caixa de seleção para Modelo 1 -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <input type="radio" name="modelo" id="modelo_1" class="form-check-input" value="1">
                            <label for="modelo_1" class="form-check-label">Modelo 1</label>
                            <div class="model-description">Modelo econômico 2 vias em 1 folha</div>
                            <button type="button" class="btn btn-info btn-sm model-preview-button" onclick="openPreview('modelo_1')">Pré-visualizar</button>
                        </div>
                    </div>

                    <!-- Caixa de seleção para Modelo 2 -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <input type="radio" name="modelo" id="modelo_2" class="form-check-input" value="2">
                            <label for="modelo_2" class="form-check-label">Modelo 2</label>
                            <div class="model-description">Descrição do Modelo 2</div>
                            <button type="button" class="btn btn-info btn-sm model-preview-button" onclick="openPreview('modelo_2')">Pré-visualizar</button>
                        </div>
                    </div>

                    <!-- Caixa de seleção para Modelo 3 -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <input type="radio" name="modelo" id="modelo_3" class="form-check-input" value="3">
                            <label for="modelo_3" class="form-check-label">Modelo 3</label>
                            <div class="model-description">Descrição do Modelo 3</div>
                            <button type="button" class="btn btn-info btn-sm model-preview-button" onclick="openPreview('modelo_3')">Pré-visualizar</button>
                        </div>
                    </div>

                    <!-- Caixa de seleção para Modelo 4 -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="form-group">
                            <input type="radio" name="modelo" id="modelo_4" class="form-check-input" value="4">
                            <label for="modelo_4" class="form-check-label">Modelo 4</label>
                            <div class="model-description">Descrição do Modelo 4</div>
                            <button type="button" class="btn btn-info btn-sm model-preview-button" onclick="openPreview('modelo_4')">Pré-visualizar</button>
                        </div>
                    </div>
                </div>

                <!-- Botão de envio -->
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Scripts do Bootstrap, jQuery e SweetAlert -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSRF Token para Requisições AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(document).ready(function() {
    $('#modeloForm').on('submit', function(event) {
        event.preventDefault(); // Evita o envio padrão do formulário

        var formData = new FormData(this);
        console.log('Dados do formulário:', Object.fromEntries(formData.entries()));

        // Atualiza o valor de campos de modelo para 0 se não estiverem selecionados
        $('input[name="modelo"]').each(function() {
            var modeloId = 'modelo_' + this.value;
            if (this.checked) {
                formData.set(modeloId, '1'); // Marca o modelo como 1
            } else {
                formData.set(modeloId, '0'); // Marca o modelo como 0
            }
        });

        console.log('Dados do formulário após adicionar 0:', Object.fromEntries(formData.entries()));

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log('Resposta do servidor:', response);
                if (response && response.success) {
                    showSuccessAlert(response.success);
                    clearErrorMessages('#modeloForm');
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000); // Recarrega a página após 2 segundos
                } else {
                    showErrorAlert(response.error || 'Sucesso');
                    displayErrorMessages(response.errors || {}, '#modeloForm');
                }
            },
            error: function(xhr) {
                console.error('Erro na solicitação AJAX:', xhr);
                showErrorAlert('Sucesso');
            }
        });
    });

    function clearErrorMessages(formSelector) {
        $(formSelector).find('.invalid-feedback').text('');
    }

    function displayErrorMessages(errors, formSelector) {
        $.each(errors, function(key, value) {
            $(formSelector).find('#' + key + 'Error').text(value[0]);
        });
    }

    function showErrorAlert(message) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: message,
            showConfirmButton: false,
            timer: 2000
        }); 
    }
    function showSuccessAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: message
        });

    }

    // Definindo a função openPreview no escopo global
    window.openPreview = function(model) {
        console.log('Abrindo pré-visualização para o modelo:', model);
        // Cria uma URL para pré-visualização
        const previewUrl = `/public/preview/${model}`;
        
        // Abre a URL de pré-visualização em uma nova janela ou aba
        window.open(previewUrl, '_blank');
    }
});

</script>
