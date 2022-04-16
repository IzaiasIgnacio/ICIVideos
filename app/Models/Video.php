<?php

namespace App\Models;

use App\Models\Traducao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Video extends Model {

	protected $table = 'video';
	protected $connection = 'icivideos';
	public $timestamps = false;
	protected $guarded = [];
	
	public static function videoExiste($titulo, $id_categoria, $id_artista, $id_tipo) {
		return Video::join('video_artista', 'video_artista.id_video', 'video.id')
						->where('video.titulo', $titulo)
						->where('video.id_categoria', $id_categoria)
						->where('video.id_tipo', $id_tipo)
						->where('video_artista.id_artista', $id_artista)
							->first();
	}

	public static function buscarOucriarVideo($titulo, $id_artista, $id_categoria, $id_tipo) {
		return Video::join('video_artista', 'video_artista.id_video', 'video.id')
						->where('video.titulo', $titulo)
						->where('video.id_categoria', $id_categoria)
						->where('video.id_tipo', $id_tipo)
						->where('video_artista.id_artista', $id_artista)
							->firstOrCreate([
								'titulo' => $titulo,
								'id_categoria' => $id_categoria,
								'id_tipo' => $id_tipo
							]);
	}

	public static function buscarVideosIndex($filtros=false) {
		$videos = Video::select('video.id', 'video.titulo', 'video.data_arquivo', 'video.duracao', 'video.favorito', 'artista.nome as artista', 'tipo.nome as tipo',
						DB::connection('icivideos')->raw("group_concat(distinct musica.titulo separator ', ') as musicas"),
						DB::connection('icivideos')->raw("group_concat(distinct tag.nome separator ', ') as tags"))
							->join('video_artista', 'video_artista.id_video', 'video.id')
							->join('artista', 'video_artista.id_artista', 'artista.id')
							->join('tipo', 'video.id_tipo', 'tipo.id')
							->leftJoin('video_musica', 'video_musica.id_video', 'video.id')
							->leftJoin('musica', 'video_musica.id_musica', 'musica.id')
							->leftJoin('video_tag', 'video_tag.id_video', 'video.id')
							->leftJoin('tag', 'video_tag.id_tag', 'tag.id');

		if (!$filtros) {
			return $videos->groupBy('video.id')->orderBy('musicas')->orderByDesc('video.id')->take(200)->get();
		}

		if (!empty($filtros['artistas'])) {
            $videos->whereIn('artista.nome', $filtros['artistas']);
		}

		if (!empty($filtros['tags'])) {
			if ($filtros['tags'][0] == 'sem_tag') {
				$videos->whereNull('video_tag.id');
			}
			else {
				$videos->whereIn('tag.nome', $filtros['tags']);
			}
		}

		if (!empty($filtros['musicas'])) {
            $videos->whereIn('musica.titulo', $filtros['musicas']);
		}

		if (!empty($filtros['titulo'])) {
            $videos->where('video.titulo', 'like', '%'.$filtros['titulo'].'%');
		}
		
		return $videos->groupBy('video.id')->orderByDesc('video.id')->get();
	}

	public function buscarDadosModal($id) {
		$this->id = $id;
		$video = Video::select('video.*', 'tipo.nome as tipo', 'categoria.nome as categoria')
						->join('video_artista', 'video_artista.id_video', 'video.id')
						->join('artista', 'video_artista.id_artista', 'artista.id')
						->join('tipo', 'video.id_tipo', 'tipo.id')
						->join('categoria', 'video.id_categoria', 'categoria.id')
						->join('video_audio', 'video_audio.id_video', 'video.id')
							->where('video.id', $id)
								->first();
		$video->duracao = \gmdate('H:i:s', $video->duracao);
		$video->tamanho = \number_format($video->tamanho / 1024, 0, '', '.'). ' MB';
		$video->audios = VideoAudio::where('id_video', $id)->get();
		$video->capturas = [
			1 => $this->buscarCaptura(1),
			2 => $this->buscarCaptura(2),
			3 => $this->buscarCaptura(3),
			4 => $this->buscarCaptura(4), 
			5 => $this->buscarCaptura(5),
			6 => $this->buscarCaptura(6),
			7 => $this->buscarCaptura(7),
			8 => $this->buscarCaptura(8)
		];
		$video->artistas = VideoArtista::join('artista', 'artista.id', 'video_artista.id_artista')->where('id_video', $video->id)->pluck('nome', 'nome')->toArray();
		$video->musicas = VideoMusica::join('musica', 'musica.id', 'video_musica.id_musica')->where('id_video', $video->id)->pluck('titulo', 'titulo')->toArray();
		$video->tags = VideoTag::join('tag', 'tag.id', 'video_tag.id_tag')->where('id_video', $video->id)->pluck('nome', 'nome')->toArray();

		$titulo_tratado = $this->traduzirTitulo($video->titulo);
		$titulo_tratado = $this->tratarTitulo($titulo_tratado);

		$musicas_titulo = $this->buscarMusicasTitulo($titulo_tratado);
		if (count($musicas_titulo) > 0) {
			$video->musicas = array_merge($video->musicas, $musicas_titulo);
		}

		$tags_titulo = $this->buscarTagsTitulo($titulo_tratado);
		if (count($tags_titulo) > 0) {
			$video->tags = array_merge($video->tags, $tags_titulo);
		}

		$video->titulo_tratado = $titulo_tratado;

		return $video;
	}

	public function buscarCaminhoCompleto() {
		return $this->caminho.'/'.$this->titulo.'.'.$this->extensao;
	}

	public function buscarCaptura($numero) {
		return Storage::disk('public')->url('capturas/'.$this->id.'_'.$numero.'.png');
	}

	public function salvar($dados) {
		try {
			$ids_artistas = array();
			$ids_musicas = array();
			$ids_tags = array();
			
			if (isset($dados['artistas'])) {
				foreach ($dados['artistas'] as $artista) {
					$a = Artista::firstOrCreate(['nome' => $artista]);
					$ids_artistas[] = $a->id;

					VideoArtista::firstOrCreate([
						'id_video' => $this->id,
						'id_artista' => $a->id
					]);
				}
			}

			if (isset($dados['musicas'])) {
				foreach ($dados['musicas'] as $musica) {
					$m = Musica::firstOrCreate(['titulo' => $musica]);
					$ids_musicas[] = $m->id;

					VideoMusica::firstOrCreate([
						'id_video' => $this->id,
						'id_musica' => $m->id
					]);
				}
			}

			if (isset($dados['tags'])) {
				foreach ($dados['tags'] as $tag) {
					$t = Tag::firstOrCreate(['nome' => $tag]);
					$ids_tags[] = $t->id;

					VideoTag::firstOrCreate([
						'id_video' => $this->id,
						'id_tag' => $t->id
					]);
				}
			}

			foreach (VideoArtista::where('id_video', $this->id)->whereNotIn('id_artista', $ids_artistas)->get() as $video_artista) {
				$video_artista->delete();
			}
			foreach (VideoMusica::where('id_video', $this->id)->whereNotIn('id_musica', $ids_musicas)->get() as $video_musica) {
				$video_musica->delete();
			}
			foreach (VideoTag::where('id_video', $this->id)->whereNotIn('id_tag', $ids_tags)->get() as $video_tag) {
				$video_tag->delete();
			}

			Musica::limpar();
			Tag::limpar();

			if ($this->titulo != $dados['titulo']) {
				Storage::disk('videos')->move($this->buscarCaminhoCompleto(), $this->caminho.'/'.$dados['titulo'].'.'.$this->extensao);
				$this->titulo = $dados['titulo'];
				$this->save();
			}

			return 'ok';
		}
        catch (\Exception $ex) {
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
        catch (\Error $ex) {
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
	}

	private function buscarMusicasTitulo($titulo_tratado) {
		$musicas = [];
		$max = 0;
		foreach (Musica::whereRaw('length(titulo) > 2')->where('titulo', '<>', 'show')->get()->pluck('titulo') as $musica) {
			if (strlen($musica) > $max && strstr(mb_strtolower($titulo_tratado), mb_strtolower($musica))) {
				$musicas = [];
				$musicas[$musica] = $musica;
				$max = strlen($musica);
			}
		}
		return $musicas;
	}

	private function buscarTagsTitulo($titulo_tratado) {
		$tags = [];
		foreach (Tag::whereRaw('length(nome) > 2')->where('nome', '<>', 'Concert')->get()->pluck('nome') as $tag) {
			if (strstr(mb_strtolower($titulo_tratado), mb_strtolower($tag))) {
				$tags[$tag] = $tag;
			}
		}

		if (strstr(mb_strtolower($titulo_tratado), 'facecam') || strstr(mb_strtolower($titulo_tratado), 'face cam')) {
			$tags['Fancam'] = 'Fancam';
		}

		return $tags;
	}

	private function traduzirTitulo($titulo) {
		$traducoes = Traducao::traducoes();

		foreach ($traducoes as $original => $traduzido) {
			$titulo = \str_replace($original, $traduzido, $titulo);
		}

		return $titulo;
	}

	private function tratarTitulo($titulo) {
		$remover = [
			"  " =>  " ",
			"()" =>  "",
			"( )" =>  "",
			"[]" =>  "",
			"[ ]" =>  ""
		];

		foreach ($remover as $remove => $replace) {
			$titulo = \str_replace($remove, $replace, $titulo);
		}

		return $titulo;
	}

	public function buscaTag($tag) {
		$id_tag = Tag::where('nome', $tag)->first()->id;
        if (VideoTag::where('id_video', $this->id)->where('id_tag', $id_tag)->exists()) {
			return ' '.$tag;
		}
	}

	public function listarTags() {
		return implode(', ',Tag::select('tag.nome')
									->join('video_tag', 'video_tag.id_tag', 'tag.id')
										->where('video_tag.id_video', $this->id)
											->pluck('nome')
												->toArray());
	}

	public static function videosSemMusica($videos) {
		return Video::leftJoin('video_musica', 'video.id', 'video_musica.id_video')
						->whereNull('video_musica.id')
						->whereIn('video.id_tipo', [1, 2])
						->whereIn('video.id', $videos)
							->count();
	}
    
}