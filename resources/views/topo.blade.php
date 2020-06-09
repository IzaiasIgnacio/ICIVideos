<nav class="navbar is-black" role="navigation" aria-label="main navigation" @php if ($db == 'icivideos_hmg') { echo "style='background-color: #950000'"; } @endphp>
    <div class='container div_index'>
        <div class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="{{route('exibir_videos')}}">Vídeos</a>
                <a class="navbar-item" href="{{route('exibir_playlists')}}">Playlists</a>
            </div>
        </div>
    </div>
</nav>