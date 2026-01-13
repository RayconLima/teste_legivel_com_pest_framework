<?php

namespace App\Enums;

enum PrioridadeEnum: string
{
    case BAIXA = 'baixa';
    case MEDIA = 'media';
    case ALTA = 'alta';
    case CRITICA = 'critica';

    public function label(): string
    {
        return match ($this) {
            self::BAIXA => 'Baixa',
            self::MEDIA => 'Média',
            self::ALTA => 'Alta',
            self::CRITICA => 'Crítica',
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::BAIXA => 'baixa',
            self::MEDIA => 'media',
            self::ALTA => 'alta',
            self::CRITICA => 'critica',
        };
    }
}
