<div class="modal" id='modal_video'>
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title"></p>
            <button class="delete fechar_modal" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="columns">
                <div class="column is-2">
                    <div class="field">
                        <label class="label">Artista</label>
                        <label class="dados_video dados_artista"></label>
                    </div>
                    <div class="field">
                        <label class="label">Duração</label>
                        <label class="dados_video dados_duracao"></label>
                    </div>
                    <div class="field">
                        <label class="label">Resolução</label>
                        <label class="dados_video dados_resolucao"></label>
                    </div>
                    <div class="field">
                        <label class="label">Formato Vídeo</label>
                        <label class="dados_video dados_formato_video"></label>
                    </div>
                    <div class="field">
                        <label class="label">FPS</label>
                        <label class="dados_video dados_fps"></label>
                    </div>
                    <div class="field">
                        <label class="label">Tamanho</label>
                        <label class="dados_video dados_tamanho"></label>
                    </div>
                    <div class="field">
                        <label class="label">Extensão</label>
                        <label class="dados_video dados_extensao"></label>
                    </div>
                    <div class="field">
                        <label class="label">Tipo</label>
                        <label class="dados_video dados_tipo"></label>
                    </div>
                    <div class="field">
                        <label class="label">Categoria</label>
                        <label class="dados_video dados_categoria"></label>
                    </div>
                    <div class='audios'></div>
                </div>
                <div class="column is-6">
                    <div class="columns is-gapless is-multiline">
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_1'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_2'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_3'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_4'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_5'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_6'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_7'>
                            </figure>
                        </div>
                        <div class="column is-6">
                            <figure class="image is-16by9">
                                <img src="" class='captura_8'>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="column is-4">
                    <input type='hidden' name='id_video' id='id_video'>
                    <div class="field">
                        <label class="label">Título</label>
                        <textarea class="input" name='titulo_tratado' id='titulo_tratado' placeholder="Título" style='height: 88px'></textarea>
                    </div>
                    <div class="field">
                        <label class="label">Músicas</label>
                        {{Form::select('musicas_modal', $musicas, null, ['placeholder' => 'Músicas', 'id' => 'musicas_modal'])}}
                    </div>
                    <div class="field">
                        <label class="label">Tags</label>
                        {{Form::select('tags_modal', $tags, null, ['placeholder' => 'Tags', 'id' => 'tags_modal'])}}
                    </div>
                    <div class="field">
                        <label class="label">Artistas</label>
                        {{Form::select('artistas_modal', $artistas, null, ['placeholder' => 'Artistas', 'id' => 'artistas_modal'])}}
                    </div>
                    <button class="button is-link btn_girar"><i class="fas fa-undo fa-flip-horizontal"></i></button>
                </div>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-pulled-right is-danger btn_excluir">Excluir</button>
            <div>
                <label class='label_progresso'></label>
                <button class="button is-link btn_salvar">Salvar</button>
                <button class="button is-light btn_cancelar">Cancelar</button>
            </div>
        </footer>
    </div>
</div>