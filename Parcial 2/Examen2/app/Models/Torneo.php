<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable(['nombre', 'juego', 'fecha', 'cupo', 'descripcion', 'estado'])]
class Torneo extends Model
{
    public const ESTADO_ABIERTO = 'abierto';

    public const ESTADO_CERRADO = 'cerrado';

    public const ESTADOS = [self::ESTADO_ABIERTO, self::ESTADO_CERRADO];

    public const CUPO_MIN = 2;

    public const CUPO_MAX = 100;

    public const CUPO_DEFECTO = 16;

    protected $attributes = [
        'cupo' => self::CUPO_DEFECTO,
        'estado' => self::ESTADO_ABIERTO,
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cupo' => 'integer',
        ];
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function jugadores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'inscripciones')->withTimestamps();
    }

    #[Scope]
    protected function disponibles(Builder $query): void
    {
        $query->where('estado', self::ESTADO_ABIERTO)
            ->whereDate('fecha', '>', today())
            ->has('inscripciones', '<', DB::raw('torneos.cupo'));
    }

    public function inscritos(): int
    {
        return $this->inscripciones_count;
    }

    public function plazasLibres(): int
    {
        return max(0, $this->cupo - $this->inscritos());
    }

    public function estaAbierto(): bool
    {
        return $this->estado === self::ESTADO_ABIERTO;
    }

    public function estaLleno(): bool
    {
        return $this->plazasLibres() === 0;
    }

    public function esFuturo(): bool
    {
        return $this->fecha->isAfter(today());
    }

    public function estaDisponible(): bool
    {
        return $this->motivoNoDisponible() === null;
    }

    public function motivoNoDisponible(): ?string
    {
        return match (true) {
            ! $this->estaAbierto() => 'Este torneo está cerrado y no acepta inscripciones.',
            ! $this->esFuturo() => 'Este torneo ya se realizó o se realiza hoy; las inscripciones terminaron.',
            $this->estaLleno() => 'Este torneo está lleno; no quedan plazas disponibles.',
            default => null,
        };
    }

    public function permiteCancelar(): bool
    {
        return $this->esFuturo();
    }

    public function tieneInscrito(?User $user): bool
    {
        return $user !== null && $this->inscripciones()->where('user_id', $user->id)->exists();
    }
}
