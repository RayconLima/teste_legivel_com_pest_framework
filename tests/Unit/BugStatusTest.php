<?php

describe('StatusPadraoEnum', function () {
    test('define o status padrao de um bug como aberto', function () {
        $status = \App\Enums\StatusEnum::ABERTO->value();
    
        expect($status)->toBe('aberto');
    });
    
    test('retorna o label correto para cada status', function () {
        expect(\App\Enums\StatusEnum::ABERTO->label())->toBe('Aberto');
        expect(\App\Enums\StatusEnum::EM_PROGRESSO->label())->toBe('Em Progresso');
        expect(\App\Enums\StatusEnum::RESOLVIDO->label())->toBe('Resolvido');
        expect(\App\Enums\StatusEnum::FECHADO->label())->toBe('Fechado');
    });
    
    test('verifica se o status pertence ao enum StatusPadraoEnum', function () {
        $validStatuses = [
            \App\Enums\StatusEnum::ABERTO->value(),
            \App\Enums\StatusEnum::EM_PROGRESSO->value(),
            \App\Enums\StatusEnum::RESOLVIDO->value(),
            \App\Enums\StatusEnum::FECHADO->value(),
        ];
    
        foreach ($validStatuses as $status) {
            expect(in_array($status, array_map(fn($case) => $case->value(), \App\Enums\StatusEnum::cases())))->toBeTrue();
        }
    
        $invalidStatus = 'invalido';
        expect(in_array($invalidStatus, array_map(fn($case) => $case->value(), \App\Enums\StatusEnum::cases())))->toBeFalse();
    });
    
    test('verifica a conversão de string para StatusPadraoEnum', function () {
        $statusString = 'resolvido';
        $statusEnum = \App\Enums\StatusEnum::from($statusString);
    
        expect($statusEnum)->toBe(\App\Enums\StatusEnum::RESOLVIDO);
    });
});
