<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['titulo', 'categoria', 'tiempo', 'dificultad', 'ingredientes', 'pasos', 'nota'])]
class Receta extends Model
{
    public const CATEGORIAS = ['Desayuno', 'Almuerzo', 'Cena', 'Postre', 'Bebida'];

    public const DIFICULTADES = [
        'facil' => 'Fácil',
        'media' => 'Media',
        'dificil' => 'Difícil',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listaIngredientes(): array
    {
        return self::lineas($this->ingredientes);
    }

    public function listaPasos(): array
    {
        return self::lineas($this->pasos);
    }

    public function dificultadTexto(): ?string
    {
        return self::DIFICULTADES[$this->dificultad] ?? null;
    }

    private static function lineas(?string $texto): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $texto))));
    }
}
