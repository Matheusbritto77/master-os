@extends('layouts.guest')

@section('content')
<div class="flex flex-col items-center mt-16">
    <!-- Contêiner da Logo -->
    <div class="mb-6">
        <img src="{{ asset('dist/assets/img/M.png') }}" alt="Master-OS Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
         </div>

    <!-- Contêiner da Mensagem -->
    <div class="container mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="bg-gray-800 text-white shadow-md rounded-lg p-6">
                    <div class="text-xl font-semibold mb-4 text-center">{{ __('Verifique Seu Endereço de Email') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="bg-green-600 text-white p-3 rounded mb-4" role="alert">
                                {{ __('Um novo link de verificação foi enviado para seu endereço de email.') }}
                            </div>
                        @endif

                        <p class="mb-4">{{ __('Antes de continuar, por favor verifique seu email para um link de verificação.') }}</p>
                        <p>{{ __('Se você não recebeu o email') }},
                            <form class="inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="text-indigo-400 hover:text-indigo-500 focus:outline-none">
                                    {{ __('clique aqui para solicitar outro') }}
                                </button>.
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
