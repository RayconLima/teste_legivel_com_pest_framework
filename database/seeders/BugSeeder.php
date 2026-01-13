<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\Bug::factory()->count(5)->create();
        // $bugs = [
        //     [
        //         'titulo' => 'Erro ao carregar página inicial',
        //         'descricao' => 'A página inicial não carrega corretamente em dispositivos móveis.',
        //         'status' => 'aberto',
        //         'prioridade' => 'alta',
        //     ],
        //     [
        //         'titulo' => 'Falha no login de usuários',
        //         'descricao' => 'Alguns usuários relatam falha ao tentar fazer login com credenciais válidas.',
        //         'status' => 'em progresso',
        //         'prioridade' => 'critica',
        //     ],
        //     [
        //         'titulo' => 'Problema na exibição de imagens',
        //         'descricao' => 'As imagens do produto não estão sendo exibidas na página de detalhes.',
        //         'status' => 'resolvido',
        //         'prioridade' => 'media',
        //     ],
        // ];

        // foreach ($bugs as $bug) {
        //     \App\Models\Bug::create($bug);
        // }
    }
}
