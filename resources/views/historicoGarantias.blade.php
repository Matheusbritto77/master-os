@extends('layouts.bar')

@section('content')
<div class="container">
    <h1>Histórico de Garantias</h1>

    <div class="mb-3">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por produto, tipo de garantia, modelo">
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Nome do Produto</th>
                <th>Tipo de Garantia</th>
                <th>Modelo do Aparelho</th>
                <th>Tempo de Garantia Produto</th>
                <th>Serviço Realizado</th>
                <th>Tempo de Garantia Serviço</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="garantiasTable">
            @foreach ($garantias as $garantia)
            <tr class="garantia-row">
                <td>{{ $garantia->id }}</td>
                <td>{{ $garantia->name }}</td>
                <td>{{ $garantia->nomeProduto }}</td>
                <td>{{ $garantia->tipoGarantia }}</td>
                <td>{{ $garantia->modeloAparelho }}</td>
                <td>{{ $garantia->tempoGarantiaProduto }}</td>
                <td>{{ $garantia->servicoRealizado }}</td>
                <td>{{ $garantia->tempoGarantiaServico }}</td>
                <td>{{ $garantia->created_at }}</td>
                <td>
                    <button class="btn btn-primary" onclick="openPdfModal('{{ route('gerar_pdf_garantia', $garantia->id) }}')">Imprimir</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Visualizar PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" style="width: 100%; height: 500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    var searchInput = $('#searchInput');

    function fetchGarantias(query = '') {
        $.ajax({
            url: "{{ route('busca_garantias') }}",
            type: 'GET',
            data: {
                query: query
            },
            success: function(data) {
                var garantiasTable = $('#garantiasTable');
                garantiasTable.empty();

                data.forEach(function(garantia) {
                    var row = `
                        <tr>
                            <td>${garantia.id}</td>
                            <td>${garantia.name}</td>
                            <td>${garantia.nomeProduto}</td>
                            <td>${garantia.tipoGarantia}</td>
                            <td>${garantia.modeloAparelho}</td>
                            <td>${garantia.tempoGarantiaProduto}</td>
                            <td>${garantia.servicoRealizado}</td>
                            <td>${garantia.tempoGarantiaServico}</td>
                            <td>${garantia.created_at}</td>
                            <td>
                                <button class="btn btn-primary" onclick="openPdfModal('{{ route('gerar_pdf_garantia', '') }}/${garantia.id}')">Imprimir</button>
                            </td>
                        </tr>
                    `;
                    garantiasTable.append(row);
                });

                hideNonMatchingRows(query);
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }

    searchInput.on('keyup', function() {
        var query = searchInput.val().trim();
        fetchGarantias(query);
    });

    function hideNonMatchingRows(query) {
        var tableRows = $('#garantiasTable').find('tr');
        tableRows.each(function(index, row) {
            var rowData = $(row).find('td');

            var found = false;
            rowData.each(function(idx, cell) {
                var cellText = $(cell).text().toLowerCase();
                if (cellText.includes(query.toLowerCase())) {
                    found = true;
                    return false;
                }
            });

            $(row).toggle(found);
        });
    }

    fetchGarantias();
});

function openPdfModal(url) {
    document.getElementById('pdfViewer').src = url;
    var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
    modal.show();
}
</script>
