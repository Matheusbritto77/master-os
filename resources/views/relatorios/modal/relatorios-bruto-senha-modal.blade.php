

<!-- resources/views/relatorios/modal/relatorios-lucro-senha-modal.blade.php -->

@extends('layouts.bar')

@section('content')
<div class="modal fade" id="senhaModal" tabindex="-1" aria-labelledby="senhaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="senhaModalLabel">Verificação de Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="senhaForm" method="POST" action="{{ route('verificar.senha.relatorio.bruto') }}">
                    @csrf
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control" required>
                        @error('senha')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Verificar Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            Swal.fire({
                title: 'Erro!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif

        // Exibe o modal ao carregar
        var senhaModal = new bootstrap.Modal(document.getElementById('senhaModal'), {
            backdrop: 'static',
            keyboard: false
        });
        senhaModal.show();
    });
</script>
@endsection
