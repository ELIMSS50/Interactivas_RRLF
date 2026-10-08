@props(['nombre', 'size' => 'size-8 text-xs'])

@php
    $palabras = preg_split('/\s+/', trim($nombre), -1, PREG_SPLIT_NO_EMPTY);
    $iniciales = '';

    foreach (array_slice($palabras, 0, 2) as $palabra) {
        $iniciales .= mb_strtoupper(mb_substr($palabra, 0, 1));
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex {$size} shrink-0 items-center justify-center rounded-full bg-indigo-100 font-semibold text-indigo-700"]) }}>{{ $iniciales }}</span>
