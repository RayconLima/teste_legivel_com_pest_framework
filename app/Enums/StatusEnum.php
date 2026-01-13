<?php

namespace App\Enums;

enum StatusEnum: string
{
    // 'aberto', 'em progresso', 'resolvido', 'fechado'
    case ABERTO = 'aberto';
    case EM_PROGRESSO = 'em progresso';
    case RESOLVIDO = 'resolvido';
    case FECHADO = 'fechado';

    public function label(): string
    {
        return match ($this) {
            self::ABERTO => 'Aberto',
            self::EM_PROGRESSO => 'Em Progresso',
            self::RESOLVIDO => 'Resolvido',
            self::FECHADO => 'Fechado',
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::ABERTO => 'aberto',
            self::EM_PROGRESSO => 'em progresso',
            self::RESOLVIDO => 'resolvido',
            self::FECHADO => 'fechado',
        };
    }
}
