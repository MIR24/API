<?php

namespace App\Library\Services\Command;


use App\Category;
use App\Library\Services\ResultOfCommand;

class GetListOfCatagories implements CommandInterface
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
