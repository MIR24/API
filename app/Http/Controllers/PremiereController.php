<?php

namespace App\Http\Controllers;

use App\Library\Services\Import\MIRTVImporter;

class PremiereController extends Controller
{
    public function index(MIRTVImporter $importer)
    {
        return response()->json($importer->getPremiere());
    }
}
