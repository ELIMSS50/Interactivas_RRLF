@props(['torneo'])

<span @class([
    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset',
    'bg-emerald-50 text-emerald-700 ring-emerald-600/20' => $torneo->estaAbierto(),
    'bg-slate-100 text-slate-600 ring-slate-500/20' => ! $torneo->estaAbierto(),
])>
    <span @class(['size-1.5 rounded-full', 'bg-emerald-500' => $torneo->estaAbierto(), 'bg-slate-400' => ! $torneo->estaAbierto()])></span>
    {{ ucfirst($torneo->estado) }}
</span>
