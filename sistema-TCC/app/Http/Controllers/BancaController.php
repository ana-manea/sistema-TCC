<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banca;
use App\Models\AvaliacaoBanca;

class BancaController extends Controller
{
    // Exibe a lista com todas as bancas cadastradas
    public function index()
    {
        // Busca todos os registros da tabela 'bancas'
        $bancas = Banca::all();
        
        // ID do usuário logado que estamos usando para testar
        $usuarioLogadoId = 3; 
    
        // Retorna a view enviando as bancas e o ID do usuário de teste
        return view('bancas.index', compact('bancas', 'usuarioLogadoId'));
    }

    // Exibe o formulário para agendar uma nova banca
    public function create()
    {
        // Abre a tela limpa de cadastro
        return view('bancas.create');
    }

    // Salva os dados da nova banca no banco de dados
    public function store(Request $request)
    {
        // Validações para evitar Erro na criação
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id|unique:bancas,tcc_id',
            'data_hora' => 'required|date',
            'local'     => 'required|string|max:255',
            'status'    => 'required|in:agendada,realizada,cancelada',
        ], [
            // Mensagens personalizadas para o usuário 
            'tcc_id.exists' => 'O TCC selecionado não existe no sistema.',
            'tcc_id.unique' => 'Este TCC já possui uma banca agendada.',
            'data_hora.date' => 'Insira uma data e hora válidas.',
        ]);

        // Cria o registro na tabela 'bancas' com os dados validados
        Banca::create($request->all());

        return redirect()->route('bancas.index')->with('sucesso', 'Banca agendada com sucesso!');
    }

    // Exibe o formulário de edição de uma banca específica
    public function edit(Banca $banca)
    {
        // Abre a tela de edição passando os dados da banca selecionada
        return view('bancas.edit', compact('banca'));
    }

    // Atualiza os dados logísticos de uma banca existente
    public function update(Request $request, Banca $banca)
    {
        // Validações robustas ignorando a própria banca que está sendo editada
        $request->validate([
            'tcc_id'    => 'required|integer|exists:tccs,id|unique:bancas,tcc_id,' . $banca->id,
            'data_hora' => 'required|date',
            'local'     => 'required|string|max:255',
            'status'    => 'required|in:agendada,realizada,cancelada',
        ], [
            'tcc_id.exists' => 'O TCC selecionado não existe no sistema.',
            'tcc_id.unique' => 'Este TCC já está vinculado a outra banca.',
            'data_hora.date' => 'Insira uma data e hora válidas.',
        ]);

        // Atualiza a linha correspondente no banco de dados
        $banca->update($request->all());

        return redirect()->route('bancas.index')->with('sucesso', 'Banca atualizada com sucesso!');
    }

    // Remove uma banca do sistema
    public function destroy(Banca $banca)
    {
        // Deleta o registro da banca selecionada
        $banca->delete();
        
        // Redireciona para a listagem avisando que foi excluída
        return redirect()->route('bancas.index')->with('sucesso', 'Banca excluída com sucesso!');
    }

     //Abre a tela exclusiva de fechamento para o Presidente da banca
    public function telaFechamento(Banca $banca)
    {
        $usuarioLogadoId = 3; // Simulação do usuário de teste

        // [PROTEÇÃO 3] Garante que só o presidente acessa a tela
        $eOPresidente = \DB::table('banca_membros')
            ->where('banca_id', $banca->id)
            ->where('usuario_id', $usuarioLogadoId)
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')->with('erro', 'Acesso negado. Apenas o Presidente da banca pode acessar esta tela.');
        }

        // Busca as notas para mostrar o resumo na tela antes de fechar
        $notas = \DB::table('avaliacoes_banca')
                    ->where('banca_id', $banca->id)
                    ->pluck('nota');

        $mediaCalculada = $notas->isEmpty() ? 0 : $notas->avg();

        return view('bancas.fechamento', compact('banca', 'mediaCalculada', 'notas'));
    }

    
     //Processa o salvamento do fechamento no banco de dados
     
    public function fecharBanca(Request $request, Banca $banca)
    {
        $usuarioLogadoId = 3; // Simulação do usuário de teste

        // [PROTEÇÃO 3] Garante no backend que quem enviou o form é o presidente
        $eOPresidente = \DB::table('banca_membros')
            ->where('banca_id', $banca->id)
            ->where('usuario_id', $usuarioLogadoId)
            ->where('papel', 'presidente')
            ->exists();

        if (!$eOPresidente) {
            return redirect()->route('bancas.index')->with('erro', 'Ação não permitida.');
        }

        // Valida os dados da conclusão
        $request->validate([
            'resultado_final' => 'required|in:aprovado,aprovado_com_ressalvas,reprovado',
            'parecer_final'   => 'required|string|max:1000',
        ], [
            'resultado_final.required' => 'O veredito final é obrigatório.',
            'parecer_final.required'   => 'O parecer/ata final é obrigatório.',
        ]);

        // Busca as notas para consolidar a média
        $notas = \DB::table('avaliacoes_banca')->where('banca_id', $banca->id)->pluck('nota');
        
        if ($notas->isEmpty()) {
            return redirect()->back()->with('erro', 'Não é possível fechar a banca sem notas lançadas.');
        }

        // Atualiza a banca de forma definitiva
        $banca->update([
            'nota_final'      => $notas->avg(),
            'resultado_final' => $request->resultado_final,
            'parecer_final'   => $request->parecer_final,
            'status'          => 'realizada',
        ]);

        return redirect()->route('bancas.index')->with('sucesso', 'Banca concluída e encerrada com sucesso!');
    }

    //exibe o resultado final da banca
    public function mostrarAta(Banca $banca)
    {
        //Garante que a banca realmente já foi realizada
        if ($banca->status != 'realizada') {
            return redirect()->route('bancas.index')->with('erro', 'Esta banca ainda não possui uma ata gerada.');
        }

        // Pega o ID e o objeto do usuário logado dinamicamente via autenticação do Laravel
        $usuarioLogadoId = auth()->id();
        $usuarioLogado = auth()->user();

        // 2. Checa se o usuário logado é um dos membros da banca (presidente ou membros)
        $eMembroDaBanca = \DB::table('banca_membros')
            ->where('banca_id', $banca->id)
            ->where('usuario_id', $usuarioLogadoId)
            ->exists();

        //Checa se o usuário logado é o orientando dono do TCC dessa banca
        $eOOrientando = ($usuarioLogado && $usuarioLogado->funcao === 'orientando' && $banca->tcc_id == $usuarioLogadoId);

        // Se não for membro da banca E não for o aluno dono do TCC, barra o acesso
        if (!$eMembroDaBanca && !$eOOrientando) {
            return redirect()->route('bancas.index')->with('erro', 'Acesso negado. Você não tem permissão para visualizar esta ata.');
        }

        // Busca os membros da banca para listar na ata
        $membros = \DB::table('banca_membros')
            ->where('banca_id', $banca->id)
            ->get();

        return view('bancas.ata', compact('banca', 'membros'));
    }
    
}