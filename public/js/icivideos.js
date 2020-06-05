$().ready(function() {

    $('.btn_modal_add_jogo').click(function() {
        $("#modal_formulario_jogo").addClass('is-active');
    });
    
    $('.fechar_modal, .btn_cancelar, .modal-background').click(function() {
        $("#modal_video").removeClass('is-active');
    });

    $(document).keyup(function(e) {
        // esc
        if (e.keyCode === 27) {
            $("#modal_video").removeClass('is-active');
        }
    });

    $(".icones .fa-edit").click(function() {
        $.post('/ICIVideos/public/ajax/dados_video_modal', {id: $(this).parent().parent().parent().find('.id_video').val()},
        function(resposta) {
            $('.dados_artista').html(resposta.artista);
            $('.dados_duracao').html(resposta.duracao);
            $('.dados_resolucao').html(resposta.resolucao);
            $('.dados_formato_video').html(resposta.formato_video);
            $('.dados_fps').html(resposta.fps);
            $('.dados_tamanho').html(resposta.tamanho);
            $('.dados_extensao').html(resposta.extensao);
            $('.dados_tipo').html(resposta.tipo);
            $('.dados_categoria').html(resposta.categoria);
            var html = '';
            $(resposta.audios).each(function(i, e) {
                html += "<div class='field'>";
                html +=     "<label class='label'>Áudio</label>";
                html +=     "<label class='dados_video'>"+e.formato+'<br>'+e.canais+" Canais</label>";
                html += "</div>";
            });
            $('.audios').html(html);
            $(".captura_1").attr('src', resposta.capturas[1]);
            $(".captura_2").attr('src', resposta.capturas[2]);
            $(".captura_3").attr('src', resposta.capturas[3]);
            $(".captura_4").attr('src', resposta.capturas[4]);
            $(".captura_5").attr('src', resposta.capturas[5]);
            $(".captura_6").attr('src', resposta.capturas[6]);
            $(".modal-card-title").html(resposta.titulo);
            $("#modal_video").addClass('is-active');
        });
    });

    $(".gerar_playlist").click(function() {
        $.post('/ICIVideos/public/ajax/gerar_playlist', {nome: $("#nome_playlist").val(), categorias: $("#categorias").tokenizer('get'),
        artistas: $("#artistas").tokenizer('get')},
        function(resposta) {
            console.log(resposta);
        });
    });

    var options = {
        create: false,
        highlight: true,
        openOnFocus: false,
        maxOptions: 10,
        maxItems: null,
        hideSelected: true,
        closeAfterSelect: true
    };

    $('#categorias').selectize(options);
    $('#artistas').selectize(options);
    $('#tags').selectize(options);
    $('#musicas').selectize(options);
    $('#artistas_remover').selectize(options);
    $('#tags_remover').selectize(options);
    $('#musicas_remover').selectize(options);

});