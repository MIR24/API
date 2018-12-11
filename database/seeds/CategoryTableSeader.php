<?php

use Illuminate\Database\Seeder;

class CategoryTableSeader extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class, 5)->make()->each(function ($category){
            $category->save();
        });
    }
}
