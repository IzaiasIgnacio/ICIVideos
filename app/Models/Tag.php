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
    
}