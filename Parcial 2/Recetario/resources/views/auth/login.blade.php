@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="max-w-sm mx-auto bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">Iniciar sesión</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium mb-1">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autofocus
                   class="w-full rounded border px-3 py-2 @error('email') border-red-500 @else border-gray-300 @enderror">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
            <input id="password" name="password" type="password"
                   class="w-full rounded border px-3 py-2 @error('password') border-red-500 @else border-gray-300 @enderror">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="recordar"> Recordarme
        </label>
        <button class="w-full rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">Entrar</button>
    </form>

    <p class="text-sm text-center mt-4">
        ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Regístrate</a>
    </p>
</div>
@endsection
