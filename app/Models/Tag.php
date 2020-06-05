<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

	protected $table = 'tag';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}