<?php

namespace App\Models;

use App\Enums\PrioridadeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'prioridade' => PrioridadeEnum::class,
    ];
}
