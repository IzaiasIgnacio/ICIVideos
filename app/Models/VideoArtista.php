<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoArtista extends Model {

	protected $table = 'video_artista';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}