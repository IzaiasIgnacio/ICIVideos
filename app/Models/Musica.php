<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Musica extends Model {

	protected $table = 'musica';
	protected $connection = 'icivideos';
	public $timestamps = false;
	protected $guarded = [];
	
	public static function limpar() {
		Musica::leftJoin('video_musica', 'video_musica.id_musica', 'musica.id')->whereNull('video_musica.id')->delete();
	}
    
}