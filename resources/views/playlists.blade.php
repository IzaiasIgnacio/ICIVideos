<div class="caixa_filtros">
    <fieldset>
        <legend>Nova Playlist</legend>
        <div class="columns">
            <div class='column is-3'>
                <label class="label">Nome</label>
                <div class="control">
                    <input type='text' class='input input_form' id='nome_playlist' name='nome_playlist' placeholder='Nome'>
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
                <label class="label">Músicas</label>
                <div class="control">
                    {{Form::select('musicas', $musicas, null, ['placeholder' => 'Músicas', 'id' => 'musicas'])}}
                </div>
                <label class="label">Tags</label>
                <div class="control">
                    {{Form::select('tags', $tags, null, ['placeholder' => 'Tags', 'id' => 'tags'])}}
                </div>
                <label class="checkbox label">
                    <input type="checkbox" name="live" id="live" value="{{App\Models\Tipo::where('nome', 'Live')->first()->id}}"> Live
                    <input type="checkbox" name="mv" id="mv" value="{{App\Models\Tipo::where('nome', 'MV')->first()->id}}"> Mv
                    <input type="checkbox" name="misc" id="misc" value="{{App\Models\Tipo::where('nome', 'Misc')->first()->id}}"> Misc
                    <input type="checkbox" name="favoritos" id="favoritos"> Favoritos
                    <!-- <input type="checkbox" name="vertical" id="vertical"> Vertical -->
                </label>
            </div>
            <div class='column is-3'>
                <label class="label">Resolução vertical mínima</label>
                <div class="control">
                    <input type='text' class='input input_form' id='resolucao' name='resolucao' placeholder='Resolução'>
                </div>
                <label class="label">FPS mínimo</label>
                <div class="control">
                    <input type='text' class='input input_form' id='fps' name='fps' placeholder='FPS'>
                </div>
                <label class="label">Dias</label>
                <!-- <label class="radio">
                    <input type="radio" name="tipo_data">
                    <input type='text' class='input is-small input_form campo_data' id='data_inicial' name='data_inicial' placeholder='Data inicial'>
                    <input type='text' class='input is-small input_form campo_data' id='data_final' name='data_final' placeholder='Data final'>
                </label>
                <br> -->
                <!-- <label class="radio"> -->
                    <!-- <input type="radio" name="tipo_data"> -->
                    <input type='text' class='input is-small input_form' id='dias' name='dias' placeholder='XXX'>
                    <!-- <div class="select is-small">
                        <select>
                            <option>Anos</option>
                            <option>Dias</option>
                            <option>Semanas</option>
                            <option>Meses</option>
                        </select>
                    </div> -->
                <!-- </label> -->
            </div>
            <div class='column is-3'>
                <h2>Remover da playlist</h2>
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
            <th>Resolução mínima</th>
            <th>Atualizado em</th>
            <th>Ações</th>
        <tr>
    <thead>
    <tbody>
        @foreach ($playlists as $playlist)
        <tr>
            <td>{{$playlist->nome}}</td>
            <td>{{$playlist->categorias}}</td>
            <td>{{$playlist->artistas}}</td>
            <td>{{$playlist->tipos}}</td>
            <td>{{$playlist->tags}}</td>
            <td>{{$playlist->favoritos}}</td>
            <td>{{$playlist->musicas}}</td>
            <td>{{$playlist->resolucao}}</td>
            <td>{{$playlist->ultima_atualizacao}}</td>
            <td class='icones' id='{{$playlist->id}}'><i class='fa fa-redo'></i></td>
        </tr>
        @endforeach
    </tbody>
</table>