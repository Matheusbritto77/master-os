@extends('layouts.bar-config')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card para Redefinir Senha -->
            <div class="card border-dark bg-dark text-light">
                <div class="card-header bg-secondary text-white">Redefinir Senha</div>

                <div class="card-body">
                    <!-- Verificação e Exibição de Mensagens de Erro -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Verificação e Exibição de Mensagens de Sucesso -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulário de Redefinição de Senha -->
                    <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="form-group">
                            <label for="current_password">Senha Atual</label>
                            <input id="current_password" type="password" class="form-control bg-secondary text-light border-dark @error('current_password') is-invalid @enderror" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Nova Senha</label>
                            <input id="password" type="password" class="form-control bg-secondary text-light border-dark @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirme a Nova Senha</label>
                            <input id="password_confirmation" type="password" class="form-control bg-secondary text-light border-dark" name="password_confirmation" required>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Salvar Senha</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr>
        </div>
    </div>
</div>
@endsection
