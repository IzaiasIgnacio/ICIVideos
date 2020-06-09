<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model {

	protected $table = 'video_tag';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}