<?php

use App\Models\Bug;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Bug Model - estrutura', function () {
    test('Deveria ter os fillable corretos', function () {
        $bug = new Bug();

        expect($bug->getFillable())->toEqual([
            'titulo',
            'descricao',
            'status',
            'prioridade',
        ]);
    });

    test('Deveria ter o nome da tabela correto', function () {
        $bug = new Bug();

        expect($bug->getTable())->toBe('bugs');
    });

    test('Deveria usar timestamps', function () {
        $bug = new Bug();

        expect($bug->usesTimestamps())->toBeTrue();
    });
});