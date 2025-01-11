@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card para Atualizar Nome, E-Mail e CPF -->
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header bg-secondary text-white">Atualizar Nome, E-Mail e CPF</div>

                <div class="card-body">
                    <!-- Formulário de atualização -->
                    <form id="updateNameEmailCpfForm" method="POST" action="{{ route('config.update', Auth::user()->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input id="name" type="text" class="form-control bg-dark text-light border-secondary" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Endereço de E-Mail</label>
                            <input id="email" type="email" class="form-control bg-dark text-light border-secondary" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cpf_cnpj">CPF ou CNPJ</label>
                            <input id="cpf_cnpj" type="text" class="form-control bg-dark text-light border-secondary" name="cpf_cnpj" value="{{ old('cpf_cnpj', Auth::user()->cpf_cnpj) }}" required>
                            @error('cpf_cnpj')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group text-right">
                            <button type="button" id="updateNameEmailCpfBtn" class="btn btn-success">
                                <i class="bi bi-save"></i> Salvar Nome, E-Mail e CPF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <hr>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.6/dist/inputmask.min.js"></script>
<script>
    $(document).ready(function() {
        // Verifica se o campo CPF/CNPJ já está preenchido e aplica a máscara corretamente
        var cpfCnpjField = $('#cpf_cnpj');
        var cpfCnpjValue = cpfCnpjField.val();

        // Aplica a máscara conforme o tipo de valor
        if (cpfCnpjValue.length === 11) {
            // Máscara para CPF
            Inputmask("99999999999").mask(cpfCnpjField);
        } else if (cpfCnpjValue.length === 14) {
            // Máscara para CNPJ
            Inputmask("99999999999999").mask(cpfCnpjField);
        } else {
            // Caso esteja vazio, habilita a máscara e a edição
            Inputmask("99999999999").mask(cpfCnpjField);
        }

        // Se o campo CPF/CNPJ já estiver preenchido, desabilita a edição
        if (cpfCnpjValue) {
            cpfCnpjField.prop('disabled', true);  // Desabilita o campo
        } else {
            cpfCnpjField.prop('disabled', false);  // Habilita o campo para edição
        }

        // Função para formatar CPF ou CNPJ
        function formatCpfCnpj(value) {
            value = value.replace(/\D/g, ''); // Remove tudo o que não for número
            if (value.length <= 11) {
                // Formata como CPF
                return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (value.length === 14) {
                // Formata como CNPJ
                return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            }
            return value;
        }

        // Formatar o CPF ou CNPJ quando for exibido na tabela ou outro local
        $('#cpf_cnpj').val(formatCpfCnpj(cpfCnpjValue));

        // Evento para salvar os dados via AJAX
        $('#updateNameEmailCpfBtn').on('click', function() {
            var form = $('#updateNameEmailCpfForm');
            var formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'),
                method: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.success,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: response.error || 'Ocorreu um erro ao processar a solicitação.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao processar a solicitação.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
</script>
