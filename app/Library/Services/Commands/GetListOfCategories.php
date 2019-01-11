<?php

namespace App\Library\Services\Commands;


use App\Category;
use App\Library\Services\ResultOfCommand;

class GetListOfCategories implements CommandInterface
{
    public function handle(array $options): ResultOfCommand
    {
        $categories = Category::GetForOldApi()->get();

        return (new ResultOfCommand())
            ->setOperation('categorylist')
            ->setContent($categories)
            ->setMessage(sprintf("Total of %d categories parsed.", count($categories)))
            ->setStatus(200);
    }
}
