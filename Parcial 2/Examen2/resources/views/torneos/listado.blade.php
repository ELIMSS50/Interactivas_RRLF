@extends('plantillas.app')

@section('title', 'Torneos disponibles')

@section('hero')
    <section class="bg-slate-900">
        <div class="mx-auto max-w-6xl px-6 py-16">
            <div>
                <div class="max-w-2xl">
                    <p class="mb-3 text-sm font-semibold tracking-wide text-indigo-400 uppercase">Inscripciones abiertas</p>
                    <h1 class="text-4xl font-bold tracking-tight text-white">Próximos torneos</h1>
                    <p class="mt-4 text-base text-slate-300">
                        Consulta los próximos torneos e inscríbete en los que tienen plazas libres.
                        @guest
                            Crea tu cuenta de jugador para inscribirte.
                        @endguest
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <x-icono name="user-plus" class="size-4" />
                                Crear cuenta de jugador
                            </a>
                            <a href="{{ route('login') }}" class="btn bg-white/10 text-white ring-1 ring-inset ring-white/20 hover:bg-white/20">
                                <x-icono name="login" class="size-4" />
                                Iniciar sesión
                            </a>
                        @else
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.torneos.index') }}" class="btn btn-primary">
                                    <x-icono name="list" class="size-4" />
                                    Gestionar torneos
                                </a>
                            @else
                                <a href="{{ route('mis-torneos') }}" class="btn btn-primary">
                                    <x-icono name="list" class="size-4" />
                                    Ver mis torneos
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('content')
    @if ($torneos->isEmpty())
        <div class="card flex flex-col items-center px-6 py-16 text-center">
            <span class="mb-4 flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                <x-icono name="inbox" class="size-6" />
            </span>
            <h2 class="text-lg font-semibold text-slate-900">No hay torneos disponibles por el momento</h2>
            <p class="mt-1 text-sm text-slate-500">Vuelve pronto para ver nuevos torneos con inscripciones abiertas.</p>
        </div>
    @else
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Torneos</h2>
            <p class="text-sm text-slate-500">Ordenados por fecha</p>
        </div>

        <div class="grid gap-5 grid-cols-3">
            @foreach ($torneos as $torneo)
                @if ($torneo->estaAbierto())
                    <a href="{{ route('torneos.show', $torneo) }}" class="card group flex flex-col p-5 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-indigo-300">
                        <div class="mb-4 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                <x-icono name="tag" class="size-3.5" />
                                {{ $torneo->juego }}
                            </span>
                            @if ($misTorneos->contains($torneo->id))
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    <x-icono name="check" class="size-3.5" />
                                    Inscrito
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-slate-900 group-hover:text-indigo-700">{{ $torneo->nombre }}</h3>

                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">
                            <x-icono name="calendar" class="size-4" />
                            <span>{{ ucfirst($torneo->fecha->translatedFormat('l j \d\e F, Y')) }}</span>
                        </div>

                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">
                            <x-icono name="users" class="size-4" />
                            <span>{{ $torneo->inscritos() }} de {{ $torneo->cupo }} inscritos · {{ $torneo->plazasLibres() }} libres</span>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 text-sm font-medium text-indigo-600">
                            Ver detalle
                            <x-icono name="arrow-right" class="size-4 transition group-hover:translate-x-0.5" />
                        </div>
                    </a>
                @else
                    <a href="{{ route('torneos.show', $torneo) }}" class="flex flex-col rounded-xl bg-slate-100 p-5 ring-1 ring-slate-200 grayscale" aria-disabled="true">
                        <div class="mb-4 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-200 px-2 py-1 text-xs font-medium text-slate-500">
                                <x-icono name="tag" class="size-3.5" />
                                {{ $torneo->juego }}
                            </span>
                            @if ($misTorneos->contains($torneo->id))
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-200 px-2 py-0.5 text-xs font-medium text-slate-500">
                                    <x-icono name="check" class="size-3.5" />
                                    Inscrito
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-slate-500">{{ $torneo->nombre }}</h3>

                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-400">
                            <x-icono name="calendar" class="size-4" />
                            <span>{{ ucfirst($torneo->fecha->translatedFormat('l j \d\e F, Y')) }}</span>
                        </div>

                        <div class="mt-2 flex items-center gap-2 text-sm text-slate-400">
                            <x-icono name="users" class="size-4" />
                            <span>{{ $torneo->inscritos() }} de {{ $torneo->cupo }} inscritos</span>
                        </div>

                        <div class="mt-5 flex items-center gap-2 border-t border-slate-200 pt-4 text-sm font-medium text-slate-500">
                            <x-icono name="lock" class="size-4" />
                            Torneo cerrado · sin inscripciones
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            {{ $torneos->links() }}
        </div>
    @endif
@endsection
