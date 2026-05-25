<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banca;

class BancaController extends Controller
{
    public function index()
    {
        // 1. Busca TODAS as bancas registradas no banco através do Model
        $bancas = Banca::all();
        // 2. Retorna a tela 'index' que está dentro da pasta 'views/bancas'
        // e envia a lista de bancas para dentro dela usando o compact()
        return view('bancas.index', compact('bancas'));
    }

    public function create()
    {
        // Abre a tela 'create.blade.php' que está na pasta 'bancas'
        return view('bancas.create');
    }

    public function store(Request $request)
    {
        // 1. Validação: Garante que os campos obrigatórios foram preenchidos
        $request->validate([
        'tcc_id' => 'required|integer',
        'data_hora' => 'required',
        'local' => 'required|string|max:255',
        'status' => 'required|string',
        ]);

        // 2. Salva no Banco: Usa o Model Banca para criar o registro com os dados do formulário
        Banca::create($request->all());

        // 3. Redireciona: Volta para a listagem com uma mensagem de sucesso
        return redirect()->route('bancas.index')->with('sucesso', 'Banca cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banca $banca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banca $banca)
    {
        // Abre a tela edit.blade.php passando os dados da banca atual
        return view('bancas.edit', compact('banca'));
    }

    public function update(Request $request, Banca $banca)
    {
        // 1. Validação com base nos ENUMs exatos do banco
        $request->validate([
            'tcc_id' => 'required|integer',
            'data_hora' => 'required',
            'local' => 'required|string|max:255',
            'status' => 'required|in:agendada,realizada,cancelada', // Valida ENUM do status
            'resultado_final' => 'nullable|in:aprovado,aprovado_com_ressalvas,reprovado', // Valida ENUM do resultado
            'nota_final' => 'nullable|numeric|min:0|max:10',
            'parecer_final' => 'nullable|string',
        ]);

        // 2. Atualiza os dados no banco
        $banca->update($request->all());

        // 3. Redireciona para a listagem
        return redirect()->route('bancas.index')->with('sucesso', 'Banca atualizada com sucesso!');
    }

    public function destroy(Banca $banca)
    {
        // 1. Deleta a banca selecionada do banco de dados
        $banca->delete();

        // 2. Redireciona de volta para a listagem com a mensagem de sucesso
        return redirect()->route('bancas.index')->with('sucesso', 'Banca excluída com sucesso!');
    }
}
