@extends('plantillas.app')

@section('title', $torneo->nombre)

@section('content')
    <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900">
        <x-icono name="arrow-left" class="size-4" />
        Volver a torneos
    </a>

    <div class="grid gap-6 grid-cols-3">
        <div class="space-y-6 col-span-2">
            <div class="card p-8">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                        <x-icono name="tag" class="size-3.5" />
                        {{ $torneo->juego }}
                    </span>
                </div>

                <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $torneo->nombre }}</h1>

                <dl class="mt-6 grid gap-4 grid-cols-3">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <dt class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase">
                            <x-icono name="calendar" class="size-4" /> Fecha
                        </dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $torneo->fecha->translatedFormat('j \d\e F, Y') }}</dd>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <dt class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase">
                            <x-icono name="users" class="size-4" /> Inscritos
                        </dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $torneo->inscritos() }} / {{ $torneo->cupo }}</dd>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <dt class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase">
                            <x-icono name="check-circle" class="size-4" /> Plazas libres
                        </dt>
                        <dd class="mt-1 font-semibold text-slate-900">{{ $torneo->plazasLibres() }}</dd>
                    </div>
                </dl>

                <div class="mt-8">
                    <h2 class="mb-2 text-sm font-semibold text-slate-900">Descripción</h2>
                    <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $torneo->descripcion ?: 'Este torneo no tiene descripción.' }}</p>
                </div>
            </div>

            <div class="card p-6">
                @auth
                    @if (auth()->user()->isJugador())
                        @if ($inscrito)
                            <div class="flex gap-4 items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                        <x-icono name="check-circle" class="size-6" />
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-900">Estás inscrito en este torneo</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $torneo->permiteCancelar() ? 'Puedes cancelar hasta el día anterior al torneo.' : 'La fecha del torneo llegó; ya no puedes cancelar.' }}
                                        </p>
                                    </div>
                                </div>
                                @if ($torneo->permiteCancelar())
                                    <form method="POST" action="{{ route('torneos.cancelar', $torneo) }}"
                                          onsubmit="return confirm('¿Cancelar tu inscripción a este torneo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <x-icono name="x-circle" class="size-4" />
                                            Cancelar inscripción
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif ($torneo->estaDisponible())
                            <div class="flex gap-4 items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">¿Quieres participar?</p>
                                    <p class="text-sm text-slate-500">Quedan {{ $torneo->plazasLibres() }} plaza(s) disponibles.</p>
                                </div>
                                <form method="POST" action="{{ route('torneos.inscribir', $torneo) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <x-icono name="user-plus" class="size-4" />
                                        Inscribirme
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-start gap-3 text-amber-800">
                                <x-icono name="warning" class="mt-0.5 size-5 text-amber-500" />
                                <p class="text-sm">{{ $torneo->motivoNoDisponible() }}</p>
                            </div>
                        @endif
                    @else
                        <div class="flex items-start gap-3 text-slate-600">
                            <x-icono name="info" class="mt-0.5 size-5 text-slate-400" />
                            <p class="text-sm">Solo los jugadores pueden inscribirse en los torneos.</p>
                        </div>
                    @endif
                @else
                    @if ($torneo->estaDisponible())
                        <div class="flex gap-4 items-center justify-between">
                            <div class="flex items-start gap-3">
                                <x-icono name="lock" class="mt-0.5 size-5 text-slate-400" />
                                <p class="text-sm text-slate-600">Inicia sesión o crea una cuenta de jugador para inscribirte.</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
                                <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3 text-amber-800">
                            <x-icono name="warning" class="mt-0.5 size-5 text-amber-500" />
                            <p class="text-sm">{{ $torneo->motivoNoDisponible() }}</p>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <aside class="card h-fit p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">Participantes</h2>
                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">{{ $participantes->count() }}</span>
            </div>

            @if ($participantes->isEmpty())
                <div class="flex flex-col items-center py-6 text-center">
                    <x-icono name="users" class="mb-2 size-8 text-slate-300" />
                    <p class="text-sm text-slate-500">Aún no hay jugadores inscritos.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($participantes as $participante)
                        <li class="flex items-center gap-3 py-2.5">
                            <span class="w-5 text-right text-xs text-slate-400">{{ $loop->iteration }}</span>
                            <x-avatar :nombre="$participante->name" />
                            <span @class(['text-sm', 'font-semibold text-indigo-700' => auth()->id() === $participante->id, 'text-slate-700' => auth()->id() !== $participante->id])>
                                {{ $participante->name }}
                                @if (auth()->id() === $participante->id)
                                    <span class="text-xs font-normal text-slate-400">(tú)</span>
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>
@endsection
