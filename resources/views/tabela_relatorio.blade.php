<table class='table is-fullwidth tabela_videos'>
    <thead>
        <tr>
            <th>Música</th>
            <th>Total</th>
            <!-- <th>MVs</th>
            <th>Lives</th> -->
            @isset($tags)
                @foreach ($tags as $tag)
                    <th>{{$tag->nome}}</th>
                @endforeach
            @endisset
        <tr>
    <thead>
    @isset($musicas)
        <tbody>
            @foreach ($musicas as $musica)
            <tr>
                <td>{{$musica->titulo}}</td>
                <td>{{$musica->total}}</td>
                <!-- <td>{{$musica->mvs}}</td>
                <td>{{$musica->lives}}</td> -->
                @isset($tags)
                    @foreach ($tags as $tag)
                        @php $nome = $tag->nome @endphp
                        <td>{{$musica->$nome}}</td>
                    @endforeach
                @endisset
            </tr>
            @endforeach
        </tbody>
    @endisset
</table>