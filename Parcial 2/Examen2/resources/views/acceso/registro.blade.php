@extends('plantillas.app')

@section('title', 'Registro')

@section('content')
    <div class="mx-auto max-w-md py-6">
        <div class="mb-8 text-center">
            <span class="mx-auto mb-4 flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
                <x-icono name="user-plus" class="size-6" />
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Crea tu cuenta de jugador</h1>
            <p class="mt-1 text-sm text-slate-500">Regístrate para inscribirte en los torneos.</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="label">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="input @error('name') input-error @enderror">
                    @error('name')
                        <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="label">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="input @error('email') input-error @enderror">
                    @error('email')
                        <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="input @error('password') input-error @enderror">
                    @error('password')
                        <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
                    @else
                        <p class="mt-1.5 text-xs text-slate-500">Mínimo 8 caracteres.</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirmar contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="input @error('password') input-error @enderror">
                </div>

                <button type="submit" class="btn btn-primary w-full">Crear cuenta</button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Inicia sesión</a>
        </p>
    </div>
@endsection
