<div class="caixa_filtros">
    <fieldset>
        <legend>Nova Playlist</legend>
        <div class="columns">
            <div class='column is-3'>
                <label class="label">Nome</label>
                <div class="control">
                    <input type='text' id='nome_playlist' name='nome_playlist'>
                </div>
                <label class="label">Categorias</label>
                <div class="control">
                    {{Form::select('categorias', $categorias, null, ['placeholder' => 'Categorias', 'id' => 'categorias'])}}
                </div>
                <label class="label">Artistas</label>
                <div class="control">
                    {{Form::select('artistas', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'artistas'])}}
                </div>
            </div>
            <div class='column is-3'>
                <label class="label">Data Inicial</label>
                <div class="control">
                    <input type='text' id='data_inicial' name='data_inicial'>
                </div>
                <label class="label">Data Final</label>
                <div class="control">
                    <input type='text' id='data_final' name='data_final'>
                </div>
                <label class="label">Tags</label>
                <div class="control">
                    {{Form::select('tags', $tags, null, ['placeholder' => 'Tags', 'id' => 'tags'])}}
                </div>
                <label class="checkbox label">
                    <input type="checkbox"> Live
                    <input type="checkbox"> Mv
                    <input type="checkbox"> Misc
                    <input type="checkbox"> Favoritos
                </label>
            </div>
            <div class='column is-3'>
                <label class="label">Músicas</label>
                <div class="control">
                    {{Form::select('musicas', $musicas, null, ['placeholder' => 'Músicas', 'id' => 'musicas'])}}
                </div>
                <label class="label">Resolução mínima</label>
                <div class="control">
                    <input type='text' id='resolucao' name='resolucao'>
                </div>
                <label class="label">Período</label>
                <div class="control">
                    <input type='text' id='valor_periodo' name='valor_periodo'>
                    <div class="select is-small">
                        <select>
                            <option>Dias</option>
                            <option>Meses</option>
                            <option>Anos</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class='column is-3'>
                <label class="label">Tags</label>
                <div class="control">
                    {{Form::select('tags_remover', $tags, null, ['placeholder' => 'Tags', 'id' => 'tags_remover'])}}
                </div>
                <label class="label">Músicas</label>
                <div class="control">
                    {{Form::select('musicas_remover', $musicas, null, ['placeholder' => 'Músicas', 'id' => 'musicas_remover'])}}
                </div>
                <label class="label">Artistas</label>
                <div class="control">
                    {{Form::select('artistas_remover', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'artistas_remover'])}}
                </div>
            </div>
        </div>
        <div class="control">
            <button class='button is-link gerar_playlist'>Gerar</button>
        </div>
    </fieldset>
</div>
<table class='table is-fullwidth tabela_videos'>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Categorias</th>
            <th>Artistas</th>
            <th>Tipos</th>
            <th>Tags</th>
            <th>Favoritos</th>
            <th>Músicas</th>
            <th>Resoluções</th>
            <th>Ações</th>
        <tr>
    <thead>
    <tbody>
        @foreach ($playlists as $playlist)
        <tr>
            <td>{{$playlist->nome}}</td>
            <td></td>
            <td>{{$playlist->artistas}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>