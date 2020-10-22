@include('modal_video')
<div class="columns is-multiline caixa_filtros">
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
    <div class='column is-3'>
        <div class="field">
            <label class="label">Músicas</label>
            {{Form::select('filtro_musicas', $musicas, null, ['placeholder' => 'Músicas', 'id' => 'filtro_musicas'])}}
        </div>
    </div>
    <div class='column is-3'>
        <div class="field">
            <label class="label">Título</label>
            {{Form::select('filtro_titulo', $titulos, null, ['placeholder' => 'Título', 'id' => 'filtro_titulo'])}}
        </div>
    </div>
    <div class='column is-offset-9 is-3'>
        <label class='total_geral'><i class="fas fa-music"></i> {{$sem_musica}} | Total: {{$total_videos}}</label>
        <br>
        <label class='total_videos'></label>
    </div>
</div>
@include('tabela_videos')