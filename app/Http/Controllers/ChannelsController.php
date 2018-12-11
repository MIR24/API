<?php

namespace App\Http\Controllers;

use App\Channel;

class ChannelsController extends Controller
{
    public function show()
    {
        return response()->json(Channel::GetForApi()->get());
    }
}
