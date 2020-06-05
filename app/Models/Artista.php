<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artista extends Model {

	protected $table = 'artista';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];

    public static function buscarCriarArtistaPorNomePasta($pasta) {
        return Artista::firstOrCreate(['nome' => $pasta]);
    }
    
}