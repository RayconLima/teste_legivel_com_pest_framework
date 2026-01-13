<?php

describe('PrioridadeEnum', function () {
    test('Deveria definir a prioridade padrao de um bug como baixa', function () {
        $prioridade = \App\Enums\PrioridadeEnum::BAIXA->value();

        expect($prioridade)->toBe('baixa');
    });

    test('Deveria retornar o label correto para cada prioridade', function () {
        expect(\App\Enums\PrioridadeEnum::BAIXA->label())->toBe('Baixa');
        expect(\App\Enums\PrioridadeEnum::MEDIA->label())->toBe('Média');
        expect(\App\Enums\PrioridadeEnum::ALTA->label())->toBe('Alta');
        expect(\App\Enums\PrioridadeEnum::CRITICA->label())->toBe('Crítica');
    });

    test('verifica a conversão de string para PrioridadeEnum', function () {
        $prioridadeString = 'alta';
        $prioridadeEnum = \App\Enums\PrioridadeEnum::from($prioridadeString);

        expect($prioridadeEnum)->toBe(\App\Enums\PrioridadeEnum::ALTA);
    });

    test('verifica se a prioridade pertence ao enum PrioridadeEnum', function () {
        $prioridadesValidas = [
            \App\Enums\PrioridadeEnum::BAIXA->value(),
            \App\Enums\PrioridadeEnum::MEDIA->value(),
            \App\Enums\PrioridadeEnum::ALTA->value(),
            \App\Enums\PrioridadeEnum::CRITICA->value(),
        ];

        foreach ($prioridadesValidas as $prioridade) {
            expect(in_array($prioridade, array_map(fn($case) => $case->value(), \App\Enums\PrioridadeEnum::cases())))->toBeTrue();
        }

        $invalidPrioridade = 'invalida';
        expect(in_array($invalidPrioridade, array_map(fn($case) => $case->value(), \App\Enums\PrioridadeEnum::cases())))->toBeFalse();
    });

    test('verifica a listagem de todas as prioridades', function () {
        $prioridades = array_map(fn($case) => $case->value(), \App\Enums\PrioridadeEnum::cases());

        expect($prioridades)->toEqual(['baixa', 'media', 'alta', 'critica']);
    });
});