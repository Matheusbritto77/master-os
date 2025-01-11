@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card para Mostrar Detalhes da Assinatura -->
            <div class="card bg-dark text-white border-secondary">
                <div class="card-header bg-primary">Detalhes da Assinatura</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @php
                        // Definindo valores padrão para teste de 7 dias
                        $defaultTipo = '7 dias ';
                        $defaultStatus = 'Ativa';
                        $defaultDataInicio = 'test';
                        $defaultDataFim = 'test';

                        // Verifica se as variáveis são nulas ou não definidas e define valores padrão
                        $tipo = $tipo ?? $defaultTipo;
                        $status = $status ?? $defaultStatus;
                        $data_inicio = $data_inicio ?? $defaultDataInicio;
                        $data_fim = $data_fim ?? $defaultDataFim;
                    @endphp

                    <div class="form-group mb-3">
                        <label for="tipo">Tipo de Assinatura</label>
                        <p id="tipo">{{ $tipo }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <p id="status">{{ $status }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="data_inicio">Data de Início</label>
                        <p id="data_inicio">{{ $data_inicio }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="data_fim">Data de Fim</label>
                        <p id="data_fim">{{ $data_fim }}</p>
                    </div>

                    <!-- Botão para Renovar a Assinatura -->
                    <div class="form-group text-end">
                        <form method="GET" action="{{ route('pagina.de.pagamento') }}">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-arrow-repeat"></i> Renovar Assinatura
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
