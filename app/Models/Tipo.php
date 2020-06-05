<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model {

	protected $table = 'tipo';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];
    
}