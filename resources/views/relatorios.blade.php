<div class="columns is-multiline caixa_filtros">
    <div class='column is-3'>
        <div class="field">
            <label class="label">Artistas</label>
            {{Form::select('relatorio_artistas', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'relatorio_artistas'])}}
        </div>
    </div>
</div>
@include('tabela_relatorio')