@extends('layouts.guest')

@section('content')
<div class="flex flex-col items-center mt-16">
    <!-- Contêiner da Logo -->
    <div class="mb-6">
        <img src="dist/assets/img/M.png" alt="Master-OS Logo" class="h-16 w-16">
    </div>

    <!-- Contêiner do Formulário -->
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="bg-gray-800 text-white shadow-md rounded-lg p-6">
                    <div class="text-xl font-semibold mb-4 text-center">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Nome -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-300">{{ __('Name') }}</label>
                                <input id="name" type="text" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-300">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <!-- CPF/CNPJ -->
                            <div class="mb-4">
                                <label for="cpf_cnpj" class="block text-sm font-medium text-gray-300">{{ __('CPF or CNPJ') }}</label>
                                <input id="cpf_cnpj" type="text" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('cpf_cnpj') is-invalid @enderror" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required autocomplete="cpf_cnpj" placeholder="Digite seu CPF ou CNPJ">

                                @error('cpf_cnpj')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <!-- Senha -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-300">{{ __('Password') }}</label>
                                <input id="password" type="password" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirmar Senha -->
                            <div class="mb-4">
                                <label for="password-confirm" class="block text-sm font-medium text-gray-300">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <!-- Botão de Envio -->
                            <div class="flex items-center justify-center">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
