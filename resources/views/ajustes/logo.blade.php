@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <!-- Visualização e edição da logo -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="logo" class="text-white">Logo do Sistema</label>
                <div class="mb-3">
                    <img src="{{ $logoUrl }}" alt="Logo do Sistema" id="logoPreview" style="max-width: 200px;">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-4 mt-md-0 text-md-right">
                <!-- Botão para abrir o modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editLogoModal">
                    Editar Logo
                </button>
                <!-- Informação de conversão de imagens -->
                <div class="mt-3">
                    <p class="text-muted" style="font-size: 0.9em;">
                        Nosso sistema só aceita formatos JPG. Utilize este <a href="https://imagem.online-convert.com/pt/converter-para-jpg" target="_blank" class="text-primary">site de conversão de imagens online</a> para converter sua logo facilmente! Essa logo será impressa na garantia e na Ordem de serviço 🎨🚀
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição da Logo -->
    <div class="modal fade" id="editLogoModal" tabindex="-1" aria-labelledby="editLogoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLogoModalLabel">Editar Logo do Sistema</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário para editar a logo -->
                    <form id="editLogoForm" action="{{ route('config.updateLogo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="logoFile">Selecione um arquivo de imagem (apenas JPG)</label>
                            <input type="file" class="form-control-file" id="logoFile" name="logoFile" accept=".jpg,.jpeg" required>
                            @error('logoFile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="updateLogoBtn" class="btn btn-primary">Salvar Logo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Scripts do Bootstrap e jQuery -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CSRF Token for AJAX Requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(document).ready(function() {
        $('#updateLogoBtn').on('click', function() {
            var form = $('#editLogoForm')[0];
            var formData = new FormData(form);

            $.ajax({
                url: $('#editLogoForm').attr('action'),
                method: $('#editLogoForm').attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        showSuccessAlert(response.success);
                        $('#editLogoModal').modal('hide');
                        clearErrorMessages('#editLogoForm');
                        $('#logoPreview').attr('src', 'data:image/jpeg;base64,' + response.logo_base64);
                    } else {
                        showErrorAlert(response.error || 'Ocorreu um erro ao processar a solicitação.');
                        displayErrorMessages(response.errors || {}, '#editLogoForm');
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

        $('#logoFile').on('change', function() {
            var input = this;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#logoPreview').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        });

        $('input').on('focus', function() {
            $(this).siblings('.invalid-feedback').text('');
        });
    });
</script>

