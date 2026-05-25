@csrf
<div>
    <label for="tema">Tema</label>
    <input type="text" name="tema">
    
    <label for="orientador_id">Orientador</label>
    <input type="text" name="orientador_id">
    
    <label for="descricao">Descrição</label>
    <textarea type="text" name="descricao" rows="10"></textarea>
    
    <label for="status">Status</label>
    <select name="status">
        <option value="em_andamento" selected>Em andamento</option>
        <option value="concluido">Concluído</option>
        <option value="cancelado">Cancelado</option>
        <option value="suspenso">Suspenso</option>
    </select>
</div>
