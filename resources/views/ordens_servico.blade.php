@extends('layouts.bar')

@section('content')
<div class="container">
    <h1>Histórico de Ordens de Serviço</h1>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Digite o nome do cliente..." oninput="searchOrdens(this.value)">

    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Cidade</th>
                    <th>CEP</th>
                    <th>Rua</th>
                    <th>Bairro</th>
                    <th>Modelo</th>
                    <th style="width: 120px;">Problema Relatado</th>
                    <th style="width: 100px;">Ações</th>
                </tr>
            </thead>
            <tbody id="ordensTableBody">
                @foreach($ordens as $ordem)
                <tr>
                    <td>{{ $ordem->cliente }}</td>
                    <td>{{ $ordem->cidade }}</td>
                    <td>{{ $ordem->cep }}</td>
                    <td>{{ $ordem->rua }}</td>
                    <td>{{ $ordem->bairro }}</td>
                    <td>{{ $ordem->modelo }}</td>
                    <td class="text-truncate" style="max-width: 120px;">{{ $ordem->problema }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="openPdfModal('{{ route('pdf', ['id' => $ordem->id]) }}')">Imprimir</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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

<script>
function searchOrdens(value) {
    var searchValue = value.toLowerCase();
    var rows = document.querySelectorAll('#ordensTableBody tr');
    rows.forEach(row => {
        var clientName = row.cells[0].textContent.toLowerCase();
        row.style.display = clientName.includes(searchValue) ? '' : 'none';
    });
}

function openPdfModal(url) {
    document.getElementById('pdfViewer').src = url;
    var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
    modal.show();
}
</script>
@endsection
