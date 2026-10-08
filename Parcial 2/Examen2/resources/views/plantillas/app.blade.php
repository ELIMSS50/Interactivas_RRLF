<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Torneos') · {{ config('app.name') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-6 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="flex size-9 items-center justify-center rounded-lg bg-indigo-600 text-white">
                    <x-icono name="trophy" class="size-5" />
                </span>
                <span class="text-lg font-bold tracking-tight text-slate-900">Torneos</span>
            </a>

            @auth
                @php
                    $enlaces = auth()->user()->isAdmin()
                        ? [['admin.dashboard', 'Panel', 'grid', 'admin.dashboard'], ['admin.torneos.index', 'Gestionar torneos', 'list', 'admin.torneos.*']]
                        : [['jugador.dashboard', 'Panel', 'grid', 'jugador.dashboard'], ['mis-torneos', 'Mis torneos', 'list', 'mis-torneos']];
                @endphp

                <nav class="flex gap-1">
                    @foreach ($enlaces as [$ruta, $texto, $icono, $patron])
                        <a href="{{ route($ruta) }}" @class([
                            'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition',
                            'bg-indigo-50 text-indigo-700' => request()->routeIs($patron),
                            'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($patron),
                        ])>
                            <x-icono :name="$icono" class="size-4" />
                            {{ $texto }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2.5">
                        <x-avatar :nombre="auth()->user()->name" />
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->isAdmin() ? 'Administrador' : 'Jugador' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary" title="Cerrar sesión">
                            <x-icono name="logout" class="size-4" />
                            <span>Salir</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        <x-icono name="login" class="size-4" />
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <x-icono name="user-plus" class="size-4" />
                        Registrarse
                    </a>
                </div>
            @endauth
        </div>
    </header>

    @hasSection('hero')
        @yield('hero')
    @endif

    <main class="mx-auto w-full max-w-6xl flex-1 px-6 py-8">
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <x-icono name="check-circle" class="mt-0.5 size-5 text-emerald-600" />
                <p>{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <x-icono name="x-circle" class="mt-0.5 size-5 text-rose-600" />
                <p>No se pudo guardar: revisa los campos marcados en rojo.</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <x-icono name="warning" class="mt-0.5 size-5 text-rose-600" />
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
