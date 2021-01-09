<!DOCTYPE html>
<html>
    <head>
        <title>ICIVídeos</title>
        @include('header')
    </head>
    <body>
        @include('topo')
        <div class='container div_index'>
            @if (isset($pagina))
                @include($pagina)
            @endif
        </div>    
    </body>
</html>