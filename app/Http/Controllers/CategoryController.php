<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{
    public function show(){

        return response()->json(Category::GetForApi()->get());
    }
}
