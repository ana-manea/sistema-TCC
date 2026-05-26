<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banca;

class BancaController extends Controller
{
    // Exibe a lista com todas as bancas cadastradas
    public function index()
    {
        // Busca todos os registros da tabela 'bancas'
        $bancas = Banca::all();
        
        // Retorna a tela de listagem enviando os dados das bancas
        return view('bancas.index', compact('bancas'));
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
}