<?php

namespace App\Http\Controllers;

use App\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function show()
    {
        return response()->json(Archive::GetForApi()->get());
    }
}
