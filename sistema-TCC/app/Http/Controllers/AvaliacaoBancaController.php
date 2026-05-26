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
        // 1. Validação dos dados de texto do formulário
        $request->validate([
            'nota' => 'required|numeric|min:0|max:10',
            'parecer' => 'required|string',
        ], [
            'nota.required' => 'A nota é obrigatória.',
            'nota.numeric' => 'A nota deve ser um número.',
            'nota.min' => 'A nota mínima é 0.',
            'nota.max' => 'A nota máxima é 10.',
            'parecer.required' => 'O parecer descritivo é obrigatório.',
        ]);

        // 2. Busca a banca e carrega os relacionamentos necessários
        $banca = Banca::with(['tcc.orientandos', 'bancaMembros'])->findOrFail($banca_id);

        // Garante que o professor logado realmente faz parte desta banca
        $professorLogadoId = auth()->id();
        $fazParteDaBanca = $banca->bancaMembros->contains('usuario_id', $professorLogadoId);

        if (!$fazParteDaBanca) {
            return redirect()->back()->withErrors(['erro' => 'Você não está cadastrado como membro avaliador desta banca.']);
        }

        // 3. Pega o ID do primeiro orientando (aluno) dono deste TCC através da pivot
        $orientando = $banca->tcc->orientandos->first();

        if (!$orientando) {
            return redirect()->back()->withErrors(['erro' => 'Este TCC não possui nenhum aluno (orientando) vinculado a ele.']);
        }

        // Garante que o professor já não enviou uma avaliação para este mesmo aluno nesta banca
        $jaAvaliou = AvaliacaoBanca::where('banca_id', $banca->id)
                                   ->where('avaliador_id', $professorLogadoId)
                                   ->where('orientando_id', $orientando->id)
                                   ->exists();

        if ($jaAvaliou) {
            return redirect()->route('bancas.index')->with('erro', 'Você já registrou a sua avaliação para este orientando nesta banca.');
        }

        // 4. Cria o registro com os dados totalmente validados 
        AvaliacaoBanca::create([
            'banca_id'      => $banca->id,
            'avaliador_id'  => $professorLogadoId,
            'orientando_id' => $orientando->id,
            'nota'          => $request->nota,
            'parecer'       => $request->parecer,
            'resultado'     => null, // Preenchido pelo presidente depois
        ]);

        return redirect()->route('bancas.index')->with('sucesso', 'Avaliação registrada com sucesso!');
    }
}