@csrf

<div class="border rounded p-3 mb-3">
    <label class="form-label" for="tema">Tema</label>
    <input class="form-control" type="text" name="tema">
    
    <label class="form-label" for="orientador_id">Orientador</label>
    <input class="form-control" type="text" name="orientador_id">
    
    <label class="form-label" for="descricao">Descrição</label>
    <textarea class="form-control" type="text" name="descricao" rows="10"></textarea>
    
    <label class="form-label" for="status">Status</label>
    <select class="form-select" name="status">
        <option value="em_andamento" selected>Em andamento</option>
        <option value="concluido">Concluído</option>
        <option value="cancelado">Cancelado</option>
        <option value="suspenso">Suspenso</option>
    </select>
</div>
