@include('modal_video')
<div class="columns is-gapless caixa_filtros">
    <div class='column is-1'>
        <h1 class="title">Filtros</h1>
    </div>
</div>
<table class='table is-fullwidth tabela_videos'>
    <thead>
        <tr>
            <th></th>
            <th>Título</th>
            <th>Tipo</th>
            <th>Data</th>
            <th align='center'>Ações</th>
        <tr>
    <thead>
    <tbody>
        @foreach ($videos as $video)
            <tr>
                <td width='10%'>
                    <figure class="image is-3x2">
                        <img src="{{Storage::disk('public')->url('capturas/'.$video->id.'_3.png')}}" />
                    </figure>
                </td>
                <td><i>{{$video->artista}}</i><br>{{$video->titulo}}</td>
                <td>{{$video->tipo}}</td>
                <td>{{$video->data_arquivo}}</td>
                <td>
                    <input type='hidden' class='id_video' value='{{$video->id}}' />
                    <div class="columns is-multiline icones">
                        <div class="column is-6">
                            <i class='fas fa-play'></i>
                        </div>
                        <div class="column is-6">
                            <i class='fas fa-edit'></i>
                        </div>
                        <div class="column is-6">
                            <i class='far fa-star'></i>
                        </div>
                        <div class="column is-6">
                            <i class='fas fa-list'></i>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>