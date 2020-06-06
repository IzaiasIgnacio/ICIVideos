<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

	public static function buscarVideosIndex() {
		return Video::select('video.id', 'video.titulo', 'video.data_arquivo', 'artista.nome as artista', 'tipo.nome as tipo')
				->join('video_artista', 'video_artista.id_video', 'video.id')
				->join('artista', 'video_artista.id_artista', 'artista.id')
				->join('tipo', 'video.id_tipo', 'tipo.id')
					->orderByDesc('data_arquivo')
						->take(50)
							->get();
	}

	public function buscarDadosModal($id) {
		$this->id = $id;
		$video = Video::select('video.*', 'artista.nome as artista', 'tipo.nome as tipo', 'categoria.nome as categoria')
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
		];

		return $video;
	}

	public function buscarCaminhoCompleto() {
		return $this->caminho.'/'.$this->titulo.'.'.$this->extensao;
	}

	public function buscarCaptura($numero) {
		return Storage::disk('public')->url('capturas/'.$this->id.'_'.$numero.'.png');
	}
    
}