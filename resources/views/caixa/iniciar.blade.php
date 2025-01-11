@extends('layouts.bar')

@section('content')
<div class="container">
    <h1>Fluxo de Caixa - Dia {{ now()->format('d/m/Y') }}</h1>
    <style>
        /* CSS para o botão personalizado */
        .btn-custom {
            background-color: #4CAF50; /* Verde */
            color: white;
        }

        .btn-custom:hover {
            background-color: #45a049; /* Verde escuro */
        }
        
        /* CSS para o botão de excluir */
        .btn-danger-custom {
            background-color: #f44336; /* Vermelho */
            color: white;
            margin-left: 5px;
        }

        .btn-danger-custom:hover {
            background-color: #e53935; /* Vermelho escuro */
        }
        
    </style>

    <!-- Botões de Ação -->
    <div class="mb-3">
        <!-- Botão para adicionar valor inicial do caixa -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarValorInicial">
            Adicionar Valor Inicial
        </button>
        
        <!-- Botões para abrir modais de registro -->
        <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalRegistrarVenda">
            Registrar Venda
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarSaida">
            Registrar Saída
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalRegistrarDespesa">
            Registrar Despesa Fixa
        </button>
        
        <!-- Botão para fechar o caixa -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFecharCaixa">
            Fechar Caixa
        </button>
    </div>
    
    <!-- Tabela de Movimentações -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Ações</th> <!-- Coluna para os botões de ação -->
            </tr>
        </thead>
        <tbody id="tabelaMovimentacoes">
            <!-- Aqui serão exibidas as movimentações do caixa -->
            <tr id="linhaValorInicial">
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td>Valor Inicial</td>
                <td>Inicial</td>
                <td id="valorInicial">R$ 0,00</td> {{-- Valor inicial --}}
                <td></td> <!-- Coluna de ação vazia para o valor inicial -->
            </tr>
            <!-- Outras movimentações serão listadas aqui -->
        </tbody>
        <tfoot>
            <!-- Total do Caixa no Dia -->
            <tr class="table-secondary" id="linhaTotalDia">
                <td colspan="4" class="text-right"><strong>Total do Dia:</strong></td>
                <td id="totalDia"><strong>R$ 0,00</strong></td> {{-- Total do dia --}}
            </tr>
        </tfoot>
    </table>
</div>

@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Função para carregar os dados na tabela
    function carregarDadosCaixas(data) {
        var tbody = $('#tabelaMovimentacoes');

        // Limpa o conteúdo atual da tabela
        tbody.empty();

        // Adiciona a linha para o valor inicial do caixa
        if (data.caixas.length > 0 && data.caixas[0].valor_inicial) {
            var valorInicialRow = '<tr id="linhaValorInicial">' +
                                    '<td>' + data.caixas[0].created_at + '</td>' +
                                    '<td>Valor Inicial</td>' +
                                    '<td>Inicial</td>' +
                                    '<td id="valorInicial">R$ ' + parseFloat(data.caixas[0].valor_inicial).toFixed(2) + '</td>' +
                                    '<td></td>' +
                                  '</tr>';
            tbody.append(valorInicialRow);
        }

        // Adiciona as linhas para vendas, saídas e despesas fixas
        for (var i = 1; i < data.caixas.length; i++) {
            var caixa = data.caixas[i];
            var tipo = caixa.venda ? 'Venda' : (caixa.saida ? 'Saída' : 'Despesa Fixa');
            var valor = caixa.venda || -caixa.saida || -caixa.despesa_fixa;
            var valorAbsoluto = Math.abs(valor);
            
            if (valor !== 0 && valor !== null) {
                var corTexto = tipo === 'Saída' || tipo === 'Despesa Fixa' ? 'red' : 'black';
                var valorFormatado = '<span style="color: ' + corTexto + ';">' + (valor < 0 ? '- ' : '') + 'R$ ' + valorAbsoluto.toFixed(2) + '</span>';
                var row = '<tr>' +
                            '<td>' + caixa.created_at + '</td>' +
                            '<td>' + caixa.descricao + '</td>' +
                            '<td>' + tipo + '</td>' +
                            '<td>' + valorFormatado + '</td>' +
                            '<td>' +
                                '<button class="btn btn-danger-custom btn-excluir" data-id="' + caixa.id + '">Excluir</button>' +
                            '</td>' +
                          '</tr>';
                tbody.append(row);
            }
        }

        // Calcula e preenche o total do dia
        var totalDia = calcularTotalDia(data.caixas);
        $('#totalDia').html('<strong>R$ ' + totalDia.toFixed(2) + '</strong>');
    }

    // Função para calcular o total do dia
    function calcularTotalDia(caixas) {
        var total = 0;

        caixas.forEach(function(caixa) {
            if (caixa.valor_inicial) {
                total += parseFloat(caixa.valor_inicial);
            }
            if (caixa.venda) {
                total += parseFloat(caixa.venda);
            }
            if (caixa.saida) {
                total -= parseFloat(caixa.saida);
            }
            if (caixa.despesa_fixa) {
                total -= parseFloat(caixa.despesa_fixa);
            }
        });

        return total;
    }

    // Função para fazer a requisição AJAX e carregar os dados
    function carregarDadosDoServidor() {
        $.ajax({
            type: "GET",
            url: "{{ route('caixas.get') }}",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    carregarDadosCaixas(response.data);
                } else {
                    console.error('Erro ao carregar os dados do servidor:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }





















    $(document).on('click', '.btn-excluir', function() {
    var id = $(this).data('id');

    // Solicita a verificação da senha
    $.ajax({
        type: "POST",
        url: '{{ route('caixas.verificarSenha', ':id') }}'.replace(':id', id),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.requiresPassword) {
                // Se a senha for necessária, exibe o modal de senha
                Swal.fire({
                    title: 'Senha necessária',
                    input: 'password',
                    inputPlaceholder: 'Digite sua senha',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    preConfirm: (senha) => {
                        if (!senha) {
                            Swal.showValidationMessage('Por favor, insira sua senha.');
                        }
                        return senha;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Envia a senha junto com o pedido de exclusão
                        processarExclusaoCaixa(id, result.value);
                    }
                });
            } else {
                // Se a senha não for necessária, apenas mostra a mensagem de sucesso
                Swal.fire(
                    'Sucesso!',
                    response.message || 'Operação realizada com sucesso.',
                    'success'
                    ).then(() => {
                    // Recarrega a página após a exclusão
                    location.reload();
                
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire(
                'Erro!',
                xhr.responseText || 'Não foi possível processar a exclusão.',
                'error'
            );
        }
    });
});

function processarExclusaoCaixa(id, senha = null) {
    $.ajax({
        type: "POST",
        url: '{{ route('caixas.verificarSenha', ':id') }}'.replace(':id', id),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            senha: senha
        },
        success: function(response) {
            if (response.success) {
                Swal.fire(
                    'Excluído!',
                    'O registro foi excluído com sucesso.',
                    'success'
                );
                // Atualiza a interface, por exemplo, recarregando a lista de registros
                carregarDadosDoServidor();
            } else {
                Swal.fire(
                    'Erro!',
                    response.message || 'Houve um problema ao excluir o registro.',
                    'error'
                );
            }
        },
        error: function(xhr, status, error) {
            Swal.fire(
                'Erro!',
                xhr.responseText || 'Não foi possível excluir o registro.',
                'error'
            );
        }
    });
}













    // Carrega os dados iniciais ao carregar a página
    $(document).ready(function() {
        carregarDadosDoServidor();
    });
</script>

@include('caixa.modal.saida')
@include('caixa.modal.venda')
@include('caixa.modal.inicial')
@include('caixa.modal.despesa')
@include('caixa.modal.fechado')
