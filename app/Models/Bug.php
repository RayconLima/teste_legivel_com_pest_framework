<?php

namespace App\Models;

use App\Enums\{StatusEnum, PrioridadeEnum};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
