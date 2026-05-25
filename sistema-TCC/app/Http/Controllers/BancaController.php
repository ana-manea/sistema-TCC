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
        // Valida se os campos obrigatórios do agendamento foram preenchidos corretamente
        $request->validate([
            'tcc_id' => 'required|integer',
            'data_hora' => 'required',
            'local' => 'required|string|max:255',
            'status' => 'required|in:agendada,realizada,cancelada',
        ]);

        // Cria o registro na tabela 'bancas' com os dados validados
        Banca::create($request->all());

        // Redireciona para a listagem com uma mensagem de sucesso
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
        // Valida as alterações feitas nos campos de agendamento
        $request->validate([
            'tcc_id' => 'required|integer',
            'data_hora' => 'required',
            'local' => 'required|string|max:255',
            'status' => 'required|in:agendada,realizada,cancelada',
        ]);

        // Atualiza a linha correspondente no banco de dados
        $banca->update($request->all());

        // Redireciona para a listagem com a mensagem de alteração salva
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