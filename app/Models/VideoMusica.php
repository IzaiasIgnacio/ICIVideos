<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoMusica extends Model {

	protected $table = 'video_musica';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}