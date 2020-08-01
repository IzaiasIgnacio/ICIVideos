@include('modal_video')
<div class="columns caixa_filtros">
    <div class='column is-3'>
        <div class="field">
            <label class="label">Artistas</label>
            {{Form::select('filtro_artistas', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'filtro_artistas'])}}
        </div>
    </div>
    <div class='column is-3'>
        <div class="field">
            <label class="label">Tags</label>
            {{Form::select('filtro_tags', $tags, null, ['placeholder' => 'Tags', 'id' => 'filtro_tags'])}}
        </div>
    </div>
    <div class='column is-3 is-offset-3'>
        <label class='total_videos'></label>
    </div>
</div>
@include('tabela_videos')