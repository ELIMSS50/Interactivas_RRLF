@extends('plantillas.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="mx-auto max-w-md py-6">
        <div class="mb-8 text-center">
            <span class="mx-auto mb-4 flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
                <x-icono name="login" class="size-6" />
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Inicia sesión</h1>
            <p class="mt-1 text-sm text-slate-500">Accede para gestionar o inscribirte en torneos.</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="label">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           class="input @error('email') input-error @enderror">
                    @error('email')
                        <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="input @error('password') input-error @enderror">
                    @error('password')
                        <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full">Entrar</button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Regístrate como jugador</a>
        </p>
    </div>
@endsection
