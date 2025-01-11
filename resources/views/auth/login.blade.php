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
                    <div class="text-xl font-semibold mb-4 text-center">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-300">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-300">{{ __('Password') }}</label>
                                <input id="password" type="password" class="mt-1 block w-full border-gray-600 bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <p class="mt-2 text-sm text-red-400">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                @enderror
                            </div>

                            <div class="mb-4 flex items-center">
                                <input class="form-checkbox h-4 w-4 text-indigo-500 border-gray-600 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-300" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="text-sm text-indigo-400 hover:text-indigo-500" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection