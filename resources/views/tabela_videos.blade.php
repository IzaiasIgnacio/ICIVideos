<table class='table is-fullwidth tabela_videos is-narrow'>
    <thead>
        <tr>
            <th width='13%'></th>
            <th width='50%'>Título</th>
            <th width='20%'>Músicas</th>
            <th width='10%'>Tags</th>
            <th width='1%'>Data</th>
            <th width='8%' align='center'>Ações</th>
        <tr>
    <thead>
    <tbody>
        @foreach ($videos as $video)
            <tr>
                <td style="vertical-align:middle">
                    <figure class="image is-3x2">
                        @if (Storage::disk('public')->exists('capturas/'.$video->id.'_3.png'))
                        <img src="{{Storage::disk('public')->url('capturas/'.$video->id.'_3.png')}}" />
                        @endif
                    </figure>
                </td>
                <td>
                    <i><b>{{$video->artista}}</b></i>
                    <br>
                    <label class='titulo_{{$video->id}}'>{{$video->titulo}}</label>
                    <br>
                    {{$video->tipo}}
                    <br>
                    {{\gmdate('H:i:s', $video->duracao)}}
                </td>
                <td><label class='td_musicas'>{{$video->musicas}}</label></td>
                <td><label class='td_tags'>{{$video->listarTags()}}</label></td>
                <td>{{date('d/m/Y', strtotime($video->data_arquivo))}}</td>
                <td>
                    <input type='hidden' class='id_video' value='{{$video->id}}' />
                    <div class="columns is-multiline icones">
                        <div class="column is-3">
                            <i class='fas fa-play'></i>
                        </div>
                        <div class="column is-3">
                            <i class='fas fa-edit'></i>
                        </div>
                        <div class="column is-3">
                            <i class="{{($video->favorito == 1) ? 'fa' : 'far'}} fa-star"></i>
                        </div>
                        <div class="column is-3">
                            <i class='fab fa-hotjar{{$video->buscaTag("hot")}}'></i>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>