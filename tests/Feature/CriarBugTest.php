<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Bug;

uses(RefreshDatabase::class);

describe('GET /api/bugs', function () {
    test('Deveria retornar uma lista vazia quando não há bugs', function () {
        $response = $this->getJson('/api/bugs');
        
        $response
            ->assertStatus(200)
            ->assertJson([]);
    });

    test('Deveria retornar uma lista de bugs quando houver registros', function () {
        Bug::factory()->count(3)->create();
        
        $response = $this->getJson('/api/bugs');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'descricao',
                        'status',
                        'prioridade',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
            ]);
    });
});

describe('POST /api/bugs', function () {
    test('Deveria ser possível reportar um bug com sucesso.', function () {
        $response = $this->postJson('/api/bugs', [
            'titulo' => 'Erro ao salvar formulário',
            'status' => 'aberto',
            'prioridade' => 'baixa',
            'descricao' => 'O sistema retorna erro 500 ao submeter',
        ]);
        
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'titulo',
                'descricao',
                'status',
                'prioridade',
                'created_at',
                'updated_at',
            ]);
    });

    test('Não deveria ser possível validar o registro de um bug sem título', function () {
        $entrada = [
            'titulo' => '',
            'descricao' => 'O sistema retorna erro 500 ao submeter',
        ];
        
        $requisicao = $this->postJson('/api/bugs', $entrada);
        $requisicao
            ->assertStatus(422)
            ->assertJsonValidationErrors(['titulo']);
    });

    test('Não deveria ser possível criar bug sem status', function () {
        $response = $this->postJson('/api/bugs', [
            'titulo' => 'teste',
            'status' => '',
            'descricao' => 'O sistema retorna erro 500 ao submeter',
        ]);
        
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    });
});

describe('PUT /api/bugs/{id}', function () {
    test('Deveria ser possível atualizar um bug', function () {
        $entrada = Bug::factory()->create();
        
        $nova_entrada = [
            'titulo' => 'Erro ao salvar formulário - atualizado',
            'status' => 'resolvido',
            'descricao' => 'O sistema retorna erro 500 ao submeter - atualizado',
        ];
        
        $response = $this->putJson("/api/bugs/{$entrada->id}", $nova_entrada);
        $response->assertStatus(200);
    });
});

describe('DELETE /api/bugs/{id}', function () {
    test('Deveria ser possível deletar um registro de bug', function () {
        $bug = Bug::factory()->create();
        
        $response = $this->deleteJson("/api/bugs/{$bug->id}");
        
        $response->assertStatus(204);
        $this->assertDatabaseMissing('bugs', ['id' => $bug->id]);
    });

    test('Não deveria ser possível deletar um bug inexistente', function () {
        // $response = $this->deleteJson('/api/bugs/999');
        // $response->assertStatus(404);
        $this->assertDatabaseCount('bugs', 0);

        $this->deleteJson('/api/bugs/9999')
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'Bug não encontrado.',
            ]);
    });

    test('Deveria retornar uma mensagem caso o bug não seja encontrado para deleção', function () {
        $response = $this->deleteJson('/api/bugs/9999');
        
        $response
            ->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'Bug não encontrado.',
            ]);
    });
});