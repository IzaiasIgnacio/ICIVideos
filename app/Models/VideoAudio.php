<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoAudio extends Model {

	protected $table = 'video_audio';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}