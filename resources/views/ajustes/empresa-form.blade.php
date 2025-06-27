@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card para Registro de Empresa -->
            <div class="card border-secondary bg-dark text-white">
                <div class="card-header bg-secondary">Registro de Empresa</div>

                <div class="card-body">
                    <!-- Formulário com AJAX -->
                    <form id="form-empresa" method="POST">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nome">Nome da Empresa</label>
                                <input type="text" name="nome" id="nome" class="form-control"
                                    value="{{ old('nome', $empresa->nome ?? '') }}" placeholder="Digite o nome da empresa" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="telefone">Telefone da Empresa</label>
                                <input type="text" name="telefone" id="telefone" class="form-control"
                                    value="{{ old('telefone', $empresa->telefone ?? '') }}" placeholder="Digite o telefone da empresa" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="cep">CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control"
                                    value="{{ old('cep', $empresa->cep ?? '') }}" placeholder="Digite o CEP" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="bairro">Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control"
                                    value="{{ old('bairro', $empresa->bairro ?? '') }}" placeholder="Digite o bairro" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="rua">Rua</label>
                                <input type="text" name="rua" id="rua" class="form-control"
                                    value="{{ old('rua', $empresa->rua ?? '') }}" placeholder="Digite a rua" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cidade">Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control"
                                    value="{{ old('cidade', $empresa->cidade ?? '') }}" placeholder="Digite a cidade" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" class="form-control" required>
                                    <option value="">Selecione o estado</option>
                                    @foreach ([
                                        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima',
                                        'SC' => 'Santa Catarina', 'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                    ] as $code => $state)
                                        <option value="{{ $code }}" {{ old('estado', $empresa->estado ?? '') == $code ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" id="btn-submit-empresa" class="btn btn-primary btn-block">Registrar Empresa</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr>
        </div>
    </div>
</div>
@endsection

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Autopreenchimento via CEP
        $('#cep').on('input', function () {
            const cep = $(this).val().replace(/\D/g, '');

            if (cep.length !== 8) return;

            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function (data) {
                if (!data.erro) {
                    $('#bairro').val(data.bairro);
                    $('#rua').val(data.logradouro);
                    $('#cidade').val(data.localidade);
                    $('#estado').val(data.uf).trigger('change');
                }
            });
        });

        // Manipulação do formulário via AJAX
        $('#form-empresa').submit(function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            // Desabilitar o botão para evitar múltiplos envios
            $('#btn-submit-empresa').prop('disabled', true).text('Salvando...');

            // Coleta dos dados do formulário
            const formData = {
                _token: $('input[name="_token"]').val(),
                nome: $('#nome').val(),
                telefone: $('#telefone').val(),
                cep: $('#cep').val(),
                bairro: $('#bairro').val(),
                rua: $('#rua').val(),
                cidade: $('#cidade').val(),
                estado: $('#estado').val()
            };

            // Realiza a requisição AJAX
            $.ajax({
                url: "{{ route('empresa.store') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.success,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Recarrega a página para mostrar os dados atualizados
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: response.message || 'Erro ao salvar dados da empresa.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Erro interno do servidor.';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Formata os erros de validação para exibição
                            const errors = xhr.responseJSON.errors;
                            const errorList = Object.values(errors).flat().map(error => `• ${error}`).join('<br>');
                            errorMessage = `Erro de validação:<br>${errorList}`;
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    // Reabilitar o botão
                    $('#btn-submit-empresa').prop('disabled', false).text('Registrar Empresa');
                }
            });
        });
    });
</script>
