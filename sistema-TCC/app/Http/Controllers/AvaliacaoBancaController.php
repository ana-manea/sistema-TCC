<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banca;
use App\Models\AvaliacaoBanca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvaliacaoBancaController extends Controller
{
    /**
     * Exibe o formulário de avaliação para um membro da banca.
     * Função: "Lançar notas" e "Dar Parecer".
     *
     * Só membros cadastrados nesta banca podem acessar.
     * Só disponível quando a banca está com status "realizada".
     */
    public function criar($banca_id)
    {
        $banca = Banca::with(['tcc.orientandos.user', 'membros'])->findOrFail($banca_id);

        // Só membros da banca acessam
        $fazParteDaBanca = $banca->membros->contains('user_id', Auth::id());
        if (!$fazParteDaBanca) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Você não está cadastrado como membro avaliador desta banca.');
        }

        // Só quando realizada
        if ($banca->status !== 'realizada') {
            return redirect()->route('bancas.show', $banca)
                ->with('erro', 'A avaliação só pode ser lançada após a banca ser confirmada como realizada.');
        }

        return view('avaliacoes.create', compact('banca'));
    }

    /**
     * Salva a nota e o parecer do membro avaliador.
     * Função: "Lançar notas" e "Dar Parecer".
     *
     * Regras:
     * - Cada membro registra apenas UMA avaliação por banca.
     * - Para TCCs em dupla, a avaliação é registrada para cada orientando separadamente.
     * - Nota de 0 a 10 (decimal).
     */
    public function store(Request $request, $banca_id)
    {
        $request->validate([
            'nota'    => 'required|numeric|min:0|max:10',
            'parecer' => 'required|string',
        ], [
            'nota.required'    => 'A nota é obrigatória.',
            'nota.numeric'     => 'A nota deve ser um número.',
            'nota.min'         => 'A nota mínima é 0.',
            'nota.max'         => 'A nota máxima é 10.',
            'parecer.required' => 'O parecer descritivo é obrigatório.',
        ]);

        $banca = Banca::with(['tcc.orientandos', 'membros'])->findOrFail($banca_id);

        $avaliadorId = Auth::id();

        // Confirma que o avaliador é membro desta banca
        $fazParteDaBanca = $banca->membros->contains('user_id', $avaliadorId);
        if (!$fazParteDaBanca) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Você não está cadastrado como membro avaliador desta banca.');
        }

        $orientandos = $banca->tcc->orientandos;

        if ($orientandos->isEmpty()) {
            return redirect()->back()
                ->with('erro', 'Este TCC não possui nenhum orientando vinculado.');
        }

        // Verifica se o avaliador já avaliou QUALQUER orientando desta banca
        // (cada membro registra apenas uma avaliação por banca, independente de dupla)
        $jaAvaliou = AvaliacaoBanca::where('banca_id', $banca->id)
            ->where('avaliador_id', $avaliadorId)
            ->exists();

        if ($jaAvaliou) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Você já registrou a sua avaliação para esta banca.');
        }

        // Para TCCs em dupla: cria uma avaliação para cada orientando com a mesma nota e parecer
        foreach ($orientandos as $orientando) {
            AvaliacaoBanca::create([
                'banca_id'      => $banca->id,
                'avaliador_id'  => $avaliadorId,
                'orientando_id' => $orientando->id,
                'nota'          => $request->nota,
                'parecer'       => $request->parecer,
                'resultado'     => null,
            ]);
        }

        return redirect()->route('bancas.show', $banca)
            ->with('sucesso', 'Avaliação registrada com sucesso!');
    }

    /**
     * Exibe o formulário de edição da própria avaliação.
     * Função: "Editar nota TCC" — prazo de 48h após o registro.
     * Somente o próprio avaliador pode editar.
     */
    public function edit(AvaliacaoBanca $avaliacaoBanca)
    {
        if ($avaliacaoBanca->avaliador_id !== Auth::id()) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Você só pode editar a sua própria avaliação.');
        }

        if ($avaliacaoBanca->created_at->diffInHours(now()) > 48) {
            return redirect()->route('bancas.index')
                ->with('erro', 'O prazo para editar a avaliação expirou (48h após o envio).');
        }

        $avaliacaoBanca->load('banca.tcc');

        return view('avaliacoes.edit', compact('avaliacaoBanca'));
    }

    /**
     * Salva as alterações da avaliação dentro do prazo de 48h.
     * Função: "Editar nota TCC".
     */
    public function update(Request $request, AvaliacaoBanca $avaliacaoBanca)
    {
        if ($avaliacaoBanca->avaliador_id !== Auth::id()) {
            return redirect()->route('bancas.index')
                ->with('erro', 'Você só pode editar a sua própria avaliação.');
        }

        if ($avaliacaoBanca->created_at->diffInHours(now()) > 48) {
            return redirect()->route('bancas.index')
                ->with('erro', 'O prazo para editar a avaliação expirou (48h após o envio).');
        }

        $request->validate([
            'nota'    => 'required|numeric|min:0|max:10',
            'parecer' => 'required|string',
        ], [
            'nota.required'    => 'A nota é obrigatória.',
            'nota.numeric'     => 'A nota deve ser um número.',
            'nota.min'         => 'A nota mínima é 0.',
            'nota.max'         => 'A nota máxima é 10.',
            'parecer.required' => 'O parecer é obrigatório.',
        ]);

        // Se o TCC é em dupla, atualiza todas as avaliações deste avaliador nesta banca
        AvaliacaoBanca::where('banca_id', $avaliacaoBanca->banca_id)
            ->where('avaliador_id', $avaliacaoBanca->avaliador_id)
            ->update([
                'nota'    => $request->nota,
                'parecer' => $request->parecer,
            ]);

        return redirect()->route('bancas.show', $avaliacaoBanca->banca_id)
            ->with('sucesso', 'Avaliação atualizada com sucesso!');
    }
}
