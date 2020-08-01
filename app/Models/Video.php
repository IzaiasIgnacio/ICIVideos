<?php

namespace App\Models;

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
			return $videos->groupBy('video.id')->orderByDesc('video.id')->take(100)->get();
		}

		if (!empty($filtros['artistas'])) {
            $videos->whereIn('artista.nome', $filtros['artistas']);
		}

		if (!empty($filtros['tags'])) {
            $videos->whereIn('tag.nome', $filtros['tags']);
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

			VideoArtista::where('id_video', $this->id)->whereNotIn('id_artista', $ids_artistas)->delete();
			VideoMusica::where('id_video', $this->id)->whereNotIn('id_musica', $ids_musicas)->delete();
			VideoTag::where('id_video', $this->id)->whereNotIn('id_tag', $ids_tags)->delete();

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
		foreach (Musica::whereRaw('length(titulo) > 2')->get()->pluck('titulo') as $musica) {
			if (strstr(mb_strtolower($titulo_tratado), mb_strtolower($musica))) {
				$musicas[$musica] = $musica;
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
		return $tags;
	}

	private function traduzirTitulo($titulo) {
		$traducoes = [
			'레드벨벳' => 'Red Velvet',
			'슬기' => 'Seulgi',
			'아이린' => 'Irene',
			'조이' => 'Joy',
			'웬디' => 'Wendy',
			'예리' => 'Yeri',
			'직캠' => 'Fancam',
			'태연' => 'Taeyeon'
		];

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

	// 	Regex rgx = new Regex(@"[^a-zA-Z0-9 (\(|\)) (\[|\]) .+_&@ \-']");
	// 	titulo = rgx.Replace(titulo, "");
	// 	Regex regex = new Regex("[ ]{2,}");
	// 	titulo = regex.Replace(titulo, " ");
	// 	titulo = titulo.Replace("  ", " ");
	// 	titulo = titulo.Replace("()", "");
	// 	titulo = titulo.Replace("( )", "");
	// 	titulo = titulo.Replace("[]", "");
	// 	titulo = titulo.Replace("[ ]", "");
	// 	return titulo;
	// }

	// private string TrataTag(string titulo, string hangul, string tag) {
	// 	if (!titulo.ToLower().Contains(tag.ToLower())) {
	// 		titulo = titulo.Replace(hangul, tag);
	// 	}
	// 	else {
	// 		titulo = titulo.Replace(tag.ToUpper(), CultureInfo.CurrentCulture.TextInfo.ToTitleCase(tag.ToLower()));
	// 		titulo = titulo.Replace(tag.ToLower(), CultureInfo.CurrentCulture.TextInfo.ToTitleCase(tag.ToLower()));
	// 	}
	// 	return titulo;
	// }
    
}