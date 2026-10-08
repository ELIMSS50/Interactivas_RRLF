@props(['torneo'])

@php
    $porcentaje = $torneo->cupo > 0 ? min(100, round($torneo->inscritos() / $torneo->cupo * 100)) : 0;
@endphp

<div {{ $attributes }}>
    <div class="mb-1.5 flex items-center justify-between text-xs">
        <span class="font-medium text-slate-700">{{ $torneo->inscritos() }} de {{ $torneo->cupo }} inscritos</span>
        <span @class(['font-medium', 'text-rose-600' => $torneo->estaLleno(), 'text-slate-500' => ! $torneo->estaLleno()])>
            {{ $torneo->estaLleno() ? 'Lleno' : $torneo->plazasLibres().' libres' }}
        </span>
    </div>
    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
        <div @class(['h-full rounded-full', 'bg-rose-500' => $torneo->estaLleno(), 'bg-indigo-600' => ! $torneo->estaLleno()]) style="width: {{ $porcentaje }}%"></div>
    </div>
</div>
