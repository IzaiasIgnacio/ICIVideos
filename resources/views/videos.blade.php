@include('modal_video')
<div class="columns is-gapless caixa_filtros">
    <div class='column is-3'>
        <div class="field">
            <label class="label">Artistas</label>
            {{Form::select('filtro_artistas', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'filtro_artistas'])}}
        </div>
    </div>
</div>
@include('tabela_videos')