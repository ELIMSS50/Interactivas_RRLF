@extends('layouts.app')

@section('titulo', 'Registro')

@section('contenido')
<div class="max-w-sm mx-auto bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">Crear cuenta</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autofocus
                   class="w-full rounded border px-3 py-2 @error('name') border-red-500 @else border-gray-300 @enderror">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium mb-1">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   class="w-full rounded border px-3 py-2 @error('email') border-red-500 @else border-gray-300 @enderror">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
            <input id="password" name="password" type="password"
                   class="w-full rounded border px-3 py-2 @error('password') border-red-500 @else border-gray-300 @enderror">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   class="w-full rounded border border-gray-300 px-3 py-2">
        </div>
        <button class="w-full rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">Registrarme</button>
    </form>

    <p class="text-sm text-center mt-4">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Inicia sesión</a>
    </p>
</div>
@endsection
