@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card para Registro de Empresa -->
            <div class="card border-secondary bg-dark text-white">
                <div class="card-header bg-secondary">Registro de Empresa</div>

                <div class="card-body">
                    <!-- Formulário para registrar ou atualizar a empresa -->
                    <form id="registerEmpresaForm">
                        @csrf

                        <div class="form-row">
                            <!-- Campo para o nome da empresa -->
                            <div class="form-group col-md-6">
                                <label for="nome">Nome da Empresa</label>
                                <input type="text" name="nome" id="nome" class="form-control" 
                                    value="{{ old('nome', $empresa->nome ?? '') }}" placeholder="Digite o nome da empresa" required>
                            </div>

                            <!-- Campo para o telefone da empresa -->
                            <div class="form-group col-md-6">
                                <label for="telefone">Telefone da Empresa</label>
                                <input type="text" name="telefone" id="telefone" class="form-control" 
                                    value="{{ old('telefone', $empresa->telefone ?? '') }}" placeholder="Digite o telefone da empresa" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Campo para o CEP -->
                            <div class="form-group col-md-4">
                                <label for="cep">CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control" 
                                    value="{{ old('cep', $empresa->cep ?? '') }}" placeholder="Digite o CEP" required>
                            </div>

                            <!-- Campo para o bairro -->
                            <div class="form-group col-md-4">
                                <label for="bairro">Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" 
                                    value="{{ old('bairro', $empresa->bairro ?? '') }}" placeholder="Digite o bairro" required>
                            </div>

                            <!-- Campo para a rua -->
                            <div class="form-group col-md-4">
                                <label for="rua">Rua</label>
                                <input type="text" name="rua" id="rua" class="form-control" 
                                    value="{{ old('rua', $empresa->rua ?? '') }}" placeholder="Digite a rua" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Campo para a cidade -->
                            <div class="form-group col-md-6">
                                <label for="cidade">Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" 
                                    value="{{ old('cidade', $empresa->cidade ?? '') }}" placeholder="Digite a cidade" required>
                            </div>

                            <!-- Campo para o estado -->
                            <div class="form-group col-md-6">
                                <label for="estado">Estado</label>
                                <select name="estado" id="estado" class="form-control" required>
                                    <option value="">Selecione o estado</option>
                                    @foreach ([
                                        'AC' => 'Acre',
                                        'AL' => 'Alagoas',
                                        'AP' => 'Amapá',
                                        'AM' => 'Amazonas',
                                        'BA' => 'Bahia',
                                        'CE' => 'Ceará',
                                        'DF' => 'Distrito Federal',
                                        'ES' => 'Espírito Santo',
                                        'GO' => 'Goiás',
                                        'MA' => 'Maranhão',
                                        'MT' => 'Mato Grosso',
                                        'MS' => 'Mato Grosso do Sul',
                                        'MG' => 'Minas Gerais',
                                        'PA' => 'Pará',
                                        'PB' => 'Paraíba',
                                        'PR' => 'Paraná',
                                        'PE' => 'Pernambuco',
                                        'PI' => 'Piauí',
                                        'RJ' => 'Rio de Janeiro',
                                        'RN' => 'Rio Grande do Norte',
                                        'RS' => 'Rio Grande do Sul',
                                        'RO' => 'Rondônia',
                                        'RR' => 'Roraima',
                                        'SC' => 'Santa Catarina',
                                        'SP' => 'São Paulo',
                                        'SE' => 'Sergipe',
                                        'TO' => 'Tocantins'
                                    ] as $code => $state)
                                        <option value="{{ $code }}" {{ (old('estado', $empresa->estado ?? '') == $code) ? 'selected' : '' }}>
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Botão de envio -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Registrar Empresa</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr>
        </div>
    </div>
</div>
@endsection

<!-- Scripts do Bootstrap, jQuery e SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#cep').on('input', function () {
            var cep = $(this).val().replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function (data) {
                if (!("erro" in data)) {
                    $('#bairro').val(data.bairro);
                    $('#rua').val(data.logradouro);
                    $('#cidade').val(data.localidade); // Preenche automaticamente a cidade
                    $('#estado').val(data.uf).trigger('change'); // Atualiza o estado e força a mudança
                }
            });
        });

        $('#registerEmpresaForm').on('submit', function (e) {
            e.preventDefault(); // Impede o envio padrão do formulário

            $.ajax({
                url: "{{ route('empresa.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        text: 'Empresa registrada com sucesso!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload(); // Recarrega a página
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Ocorreu um erro ao registrar a empresa. Por favor, tente novamente.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
