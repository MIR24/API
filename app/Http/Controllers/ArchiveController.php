<?php

namespace App\Http\Controllers;

use App\Archive;
use App\Library\Services\TimeReplacer\TimeReplacer;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function show(TimeReplacer $replacer)
    {
        return response()->json( $replacer->replace(Archive::GetForApi()->get()));
    }
}
