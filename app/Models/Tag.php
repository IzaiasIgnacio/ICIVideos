<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'tag';
	protected $connection = 'icivideos';
	public $timestamps = false;
	protected $guarded = [];
	
	public static function limpar() {
		Tag::leftJoin('video_tag', 'video_tag.id_tag', 'tag.id')->whereNull('video_tag.id')->delete();
	}
    
	public static function buscarTagsArtista($artista) {
		return Tag::select('tag.id', 'tag.nome')
					->join('video_tag', 'video_tag.id_tag', 'tag.id')			
					->join('video_artista', 'video_artista.id_video', 'video_tag.id_video')
						->where('id_artista', $artista)
							->groupBy('tag.id')
								->orderBy('nome')
									->get();
	}

}