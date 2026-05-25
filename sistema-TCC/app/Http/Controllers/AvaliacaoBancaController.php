<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banca; 
use App\Models\AvaliacaoBanca;

class AvaliacaoBancaController extends Controller
{
    // 1. Tela para o professor preencher a nota e o parecer
    public function criar($banca_id)
    {
        // Busca a banca para sabermos quem está sendo avaliado
        $banca = Banca::findOrFail($banca_id);
        
        return view('avaliacoes.create', compact('banca'));
    }

    // 2. Salva a nota e o parecer no banco de dados
    public function store(Request $request, $banca_id)
    {
        // 1. Validação dos dados do formulário
        $request->validate([
            'nota' => 'required|numeric|min:0|max:10',
            'parecer' => 'required|string',
        ]);

        // 2. Busca a banca e carrega o TCC com os orientandos vinculados a ele
        $banca = Banca::with('tcc.orientandos')->findOrFail($banca_id);

        // 3. Pega o ID do primeiro orientando (aluno) dono deste TCC através da pivot
        // O Laravel busca na relação: banca -> tcc -> orientandos (da tabela pivot tcc_orientandos)
        $orientando = $banca->tcc->orientandos->first();

        // Caso por algum erro de teste o TCC não tenha aluno vinculado na pivot
        if (!$orientando) {
            return redirect()->back()->withErrors(['erro' => 'Este TCC não possui nenhum aluno (orientando) vinculado a ele.']);
        }

        // 4. Cria o registro com os IDs perfeitamente alinhados ao seu banco físico
        AvaliacaoBanca::create([
            'banca_id'      => $banca->id,
            'avaliador_id'  => auth()->id(),   // ID do professor logado no sistema
            'orientando_id' => $orientando->id, // O ID vindo da tabela 'orientandos' que descobrimos automaticamente
            'nota'          => $request->nota,
            'parecer'       => $request->parecer,
            'resultado'     => null,            // Aceita NULL por padrão, preenchido pelo presidente depois
        ]);

        return redirect()->route('bancas.index')->with('sucesso', 'Avaliação registrada com sucesso!');
    }
}
