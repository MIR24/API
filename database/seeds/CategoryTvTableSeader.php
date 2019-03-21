<?php

use Illuminate\Database\Seeder;

class CategoryTvTableSeader extends Seeder
{
    public function run()
    {
        $categories = config('channels.categories_tv');
        foreach ($categories as $categoryData) {
            (new \App\CategoryTv($categoryData))->save();
        }
    }
}
