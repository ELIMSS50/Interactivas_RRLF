<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Recetario')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">
    <nav class="bg-white border-b">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('recetas.index') }}" class="font-bold text-lg text-orange-600">Recetario</a>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-600 hover:text-red-600">Cerrar sesión</button>
                    </form>
                </div>
            @else
                <div class="flex gap-4 text-sm">
                    <a href="{{ route('login') }}" class="hover:text-orange-600">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="hover:text-orange-600">Registrarse</a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-6">
        @if (session('ok'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-2 text-green-800">
                {{ session('ok') }}
            </div>
        @endif

        @yield('contenido')
    </main>
</body>
</html>
