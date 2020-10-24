<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use File;

class ExportarController extends Controller {

    public function exportar() {
        $dump = "I:\\xampp\\htdocs\\ICIVideos\dump_local.sql";
        shell_exec("I:\\xampp\\mysql\\bin\\mysqldump -u root icivideos > ".$dump);
        Storage::disk('drive')->put('db_icivideos.sql', File::get($dump));
        unlink($dump);
    }

}