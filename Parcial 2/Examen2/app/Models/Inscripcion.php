<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['torneo_id', 'user_id'])]
class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
