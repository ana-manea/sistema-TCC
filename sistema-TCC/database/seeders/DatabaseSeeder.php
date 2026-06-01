<?php

namespace Database\Seeders;

use App\Models\AvaliacaoBanca;
use App\Models\Banca;
use App\Models\BancaMembro;
use App\Models\Entrega;
//use App\Models\Feedback;
use App\Models\HistoricoTcc;
use App\Models\Orientador;
use App\Models\Orientando;
use App\Models\Reuniao;
use App\Models\Tarefa;
use App\Models\Tcc;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admins = collect();
        foreach ([
            ['Administrador 1', 'admin1@email.com'],
            ['Ana', 'ac.manea.b@gmail.com'],
        ] as $dados) {

            $admins->push(
                User::create([
                    'name' => $dados[0],
                    'email' => $dados[1],
                    'password' => Hash::make('admin123'),
                    'funcao' => 'admin',
                    'avatar' => '#b20000',
                ])
            );
        }

        $orientadores = collect();
        foreach ([
            ['Profa. Ana Orientadora', 'orientador1@email.com', 'Engenharia de Software'],
            ['Prof. Bruno Orientador', 'orientador2@email.com', 'Banco de Dados'],
        ] as $dados) {
            $user = User::create([
                'name' => $dados[0],
                'email' => $dados[1],
                'password' => Hash::make('orientador123'),
                'funcao' => 'orientador',
                'avatar' => '#0055cc',
            ]);

            $orientadores->push(Orientador::create([
                'user_id' => $user->id,
                'area_atuacao' => $dados[2],
                'disponibilidade' => 'Segunda e quarta-feira, 14h às 17h',
                'max_orientandos' => 5,
            ]));
        }

        $membros = collect();
        foreach ([
            ['Presidente Banca', 'presidente@email.com'],
            ['Membro Interno', 'interno@email.com'],
            ['Membro Externo', 'externo@email.com'],
        ] as $dados) {
            $membros->push(User::create([
                'name' => $dados[0],
                'email' => $dados[1],
                'password' => Hash::make('banca123'),
                'funcao' => 'membro_banca',
                'avatar' => '#6600cc',
            ]));
        }

        $orientandos = collect();
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => 'Aluno ' . $i,
                'email' => 'aluno' . $i . '@email.com',
                'password' => Hash::make('aluno123'),
                'funcao' => 'orientando',
                'avatar' => '#009933',
            ]);

            $orientandos->push(Orientando::create([
                'user_id' => $user->id,
                'orientador_id' => $orientadores[$i % 2]->id,
                'matricula' => '20250' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'curso' => 'Sistemas de Informação',
                'semestre' => 8,
            ]));
        }

        $tccEmAndamento = Tcc::create([
            'tema' => 'Sistema de Gerenciamento de TCC',
            'descricao' => 'Projeto em andamento para acompanhamento de orientações, entregas e bancas.',
            'status' => 'em_andamento',
            'orientador_id' => $orientadores[0]->id,
        ]);
        DB::table('tcc_orientandos')->insert(['tcc_id' => $tccEmAndamento->id, 'orientando_id' => $orientandos[0]->id]);

        Tarefa::create([
            'tcc_id' => $tccEmAndamento->id,
            'titulo' => 'Revisar introdução',
            'descricao' => 'Ajustar objetivo geral e objetivos específicos.',
            'prazo' => now()->addDays(7)->toDateString(),
            'status' => 'pendente',
        ]);

        Entrega::create([
            'tcc_id' => $tccEmAndamento->id,
            'titulo' => 'Entrega parcial',
            'descricao' => 'Primeira versão do texto do TCC.',
            'prazo' => now()->addDays(10)->toDateString(),
            'status' => 'pendente',
        ]);

        Reuniao::create([
            'tcc_id' => $tccEmAndamento->id,
            'data_hora' => now()->addDays(3),
            'local' => 'Google Meet',
            'observacoes' => 'Reunião de acompanhamento.',
            'proximos_passos' => 'Finalizar revisão bibliográfica.',
            'status' => 'agendada',
        ]);

        /*Feedback::create([
            'tcc_id' => $tccEmAndamento->id,
            'orientador_id' => $orientadores[0]->id,
            'descricao' => 'Melhorar a descrição do problema e justificar a escolha da tecnologia.',
        ]);*/

        $tccConcluido = Tcc::create([
            'tema' => 'Aplicação Web para Controle Acadêmico',
            'descricao' => 'Projeto demonstrativo já avaliado pela banca.',
            'status' => 'concluido',
            'resultado_final' => 'aprovado',
            'nota_final' => 8.50,
            'orientador_id' => $orientadores[1]->id,
        ]);
        DB::table('tcc_orientandos')->insert(['tcc_id' => $tccConcluido->id, 'orientando_id' => $orientandos[1]->id]);

        $banca = Banca::create([
            'tcc_id' => $tccConcluido->id,
            'data_hora' => now()->subDays(5),
            'local' => 'Sala 12',
            'status' => 'realizada',
            'parecer_final' => 'Banca realizada. Trabalho aprovado.',
            'resultado_final' => 'aprovado',
            'nota_final' => 8.50,
        ]);

        foreach ([
            'presidente' => $membros[0]->id,
            'membro_interno' => $membros[1]->id,
            'membro_externo' => $membros[2]->id,
        ] as $papel => $userId) {
            BancaMembro::create(['banca_id' => $banca->id, 'user_id' => $userId, 'papel' => $papel]);
            AvaliacaoBanca::create([
                'banca_id' => $banca->id,
                'orientando_id' => $orientandos[1]->id,
                'avaliador_id' => $userId,
                'nota' => $papel === 'membro_externo' ? 8.0 : 8.75,
                'parecer' => 'Avaliação registrada para demonstração.',
            ]);
        }

        HistoricoTcc::create([
            'tcc_id' => $tccConcluido->id,
            'alterado_por' => $admins[0]->id,
            'status_anterior' => 'em_andamento',
            'status_novo' => 'concluido',
            'observacao' => 'Registro demonstrativo de fechamento de banca.',
        ]);
    }
}
