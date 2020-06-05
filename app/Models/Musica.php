<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Musica extends Model {

	protected $table = 'musica';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}