<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model - estrutura', function () {
    test('Deveria usar timestamps', function () {
        $user = new User();

        expect($user->usesTimestamps())->toBeTrue();
    });
});