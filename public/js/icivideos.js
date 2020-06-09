var select_artistas_modal;
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

    $('.div_index').on('click', '.icones .fa-edit', function() {
        $.post('/ICIVideos/public/ajax/dados_video_modal', {id: $(this).parent().parent().parent().find('.id_video').val()},
        function(resposta) {
            // console.log(resposta);
            $(".label_progresso").html('');
            $('.dados_artista').html(Object.values(resposta.artistas));
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
            $(".captura_7").attr('src', resposta.capturas[7]);
            $(".captura_8").attr('src', resposta.capturas[8]);
            $(".modal-card-title").html(resposta.titulo);
            if (resposta.artistas != undefined) {
                select_artistas_modal[0].selectize.setValue(Object.keys(resposta.artistas));
            }
            if (resposta.musicas != undefined) {
                select_musicas_modal[0].selectize.setValue(Object.keys(resposta.musicas));
            }
            if (resposta.tags != undefined) {
                select_tags_modal[0].selectize.setValue(Object.keys(resposta.tags));
            }
            $("#titulo_tratado").val(resposta.titulo_tratado);
            $("#id_video").val(resposta.id);
            $("#modal_video").addClass('is-active');
        });
    });

    $(".gerar_playlist").click(function() {
        var tipos = [];
        if ($("#mv").is(':checked')) {
            tipos.push($("#mv").val());
        }
        if ($("#live").is(':checked')) {
            tipos.push($("#live").val());
        }
        if ($("#misc").is(':checked')) {
            tipos.push($("#misc").val());
        }
        $.post('/ICIVideos/public/ajax/gerar_playlist', {
        nome: $("#nome_playlist").val(),
        categorias: $("#categorias").val(),
        artistas: $("#artistas").val(),
        tags: $("#tags").val(),
        musicas: $("#musicas").val(),
        tipos: tipos,
        favoritos: $("#favoritos").is(":checked"),
        resolucao: $("#resolucao").val()},
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

    var options_modal = {
        create: true,
        highlight: true,
        openOnFocus: false,
        maxOptions: 10,
        maxItems: null,
        hideSelected: true,
        closeAfterSelect: true,
        render: {
            option_create: function() {
                return "<div class='create'></div>";
            }
        }
    };

    var option_filtros = {
        create: false,
        highlight: true,
        openOnFocus: false,
        maxOptions: 20,
        maxItems: null,
        hideSelected: true,
        openOnFocus: true,
        dropdownParent: "body",
        closeAfterSelect: true,
        onItemAdd(value, item) {
            var artistas = $("#filtro_artistas").val();
            artistas.push(item[0].innerHTML);
            $.post('/ICIVideos/public/ajax/filtrar_videos', {
                artistas: artistas
            },
            function(resposta) {
                $(".tabela_videos").html(resposta.html);
            });
        }
    };

    $('#categorias').selectize(options);
    $('#artistas').selectize(options);
    $('#tags').selectize(options);
    $('#musicas').selectize(options);
    $('#artistas_remover').selectize(options);
    $('#tags_remover').selectize(options);
    $('#musicas_remover').selectize(options);

    $('#filtro_artistas').selectize(option_filtros);

    select_musicas_modal = $('#musicas_modal').selectize(options_modal);
    select_tags_modal = $('#tags_modal').selectize(options_modal);
    select_artistas_modal = $('#artistas_modal').selectize(options_modal);

    $('.campo_data').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoApply: true,
        locale: {
            "format": "DD/MM/YYYY",
            "daysOfWeek": [
                "Dom",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "Sáb"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        }
    });

    $('.campo_data').val('');

    $('.btn_salvar').click(function() {
        var linha = $(".titulo_"+$("#id_video").val()).parent().parent();
        $.post('/ICIVideos/public/ajax/salvar_video', {
            id_video: $("#id_video").val(),
            titulo: $("#titulo_tratado").val(),
            artistas: $("#artistas_modal").val(),
            musicas: $("#musicas_modal").val(),
            tags: $("#tags_modal").val()
        },
        function(resposta) {
            console.log(resposta);
            if (resposta == 'ok') {
                $("#modal_video").removeClass('is-active');
                $(".titulo_"+$("#id_video").val()).html($("#titulo_tratado").val());
                linha.find('.td_musicas').html($("#musicas_modal").val());
                linha.find('.td_tags').html($("#tags_modal").val());
            }
            else {
                $(".label_progresso").html(resposta);
            }
        });
    });

    $('.div_index').on('click', '.fa-play', function() {
        $.post('/ICIVideos/public/ajax/play', {id: $(this).parent().parent().parent().find('.id_video').val()},
        function() {
            window.location = "play:K:\\Vídeos\\play.m3u";
        });
    });

    $('.div_index').on('click', '.fa-star', function() {
        var star = $(this);
        $.post('/ICIVideos/public/ajax/favorito', {id: $(this).parent().parent().parent().find('.id_video').val()},
        function() {
            star.toggleClass('fa far');
        });
    });

});