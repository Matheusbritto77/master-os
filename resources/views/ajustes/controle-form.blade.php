@extends('layouts.bar-config')

@section('content')
<div class="card bg-dark text-white">
    <div class="card-header">Controle de Acesso</div>
    <div class="card-body">
        <!-- Formulário de Controle de Acesso -->
        <form id="registerControleForm" method="POST" action="{{ route('register.controle') }}">
            @csrf
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" class="form-control bg-dark text-white" required>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="ajustes" id="ajustes" class="form-check-input" value="1">
                <label for="ajustes" class="form-check-label">Ajustes</label>
                <div class="invalid-feedback" id="ajustesError"></div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="historico_de_caixa" id="historico_de_caixa" class="form-check-input" value="1">
                <label for="historico_de_caixa" class="form-check-label">Histórico de Caixa</label>
                <div class="invalid-feedback" id="historico_de_caixaError"></div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="relatorio_lucro" id="relatorio_lucro" class="form-check-input" value="1">
                <label for="relatorio_lucro" class="form-check-label">Relatório de Lucro</label>
                <div class="invalid-feedback" id="relatorio_lucroError"></div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="relatorio_bruto" id="relatorio_bruto" class="form-check-input" value="1">
                <label for="relatorio_bruto" class="form-check-label">Relatório Bruto</label>
                <div class="invalid-feedback" id="relatorio_brutoError"></div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" name="excluir_registro_caixa" id="excluir_registro_caixa" class="form-check-input" value="1">
                <label for="excluir_registro_caixa" class="form-check-label">Excluir Registro de Caixa</label>
                <div class="invalid-feedback" id="excluir_registro_caixaError"></div>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Controle</button>
        </form>
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
        $('#registerControleForm').on('submit', function(event) {
            event.preventDefault(); // Evita o envio padrão do formulário

            var formData = new FormData(this);

            // Adiciona valor 0 para checkboxes desmarcados
            $('input[type="checkbox"]').each(function() {
                if (!formData.has(this.name)) {
                    formData.append(this.name, 0);
                }
            });

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        showSuccessAlert(response.success);
                        clearErrorMessages('#registerControleForm');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000); // Recarrega a página após 2 segundos
                    } else {
                        showErrorAlert(response.error || 'Ocorreu um erro ao processar a solicitação.');
                        displayErrorMessages(response.errors || {}, '#registerControleForm');
                    }
                },
                error: function(xhr) {
                    showErrorAlert('Ocorreu um erro ao processar a solicitação.');
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

        function showSuccessAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: message,
                showConfirmButton: false,
                timer: 2000
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: message
            });
        }

        $('input').on('focus', function() {
            $(this).siblings('.invalid-feedback').text('');
        });
    });
</script>
