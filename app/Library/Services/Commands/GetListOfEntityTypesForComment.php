<?php

namespace App\Library\Services\Commands;


use App\EntityTypeForComment;
use App\Library\Services\ResultOfCommand;


class GetListOfEntityTypesForComment implements CommandInterface
{
    private const OPERATION = "types";

    public function handle(array $options): ResultOfCommand
    {
        $types = EntityTypeForComment::all();

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent($types)
            ->setMessage(sprintf("Total of %d types.", count($types)))
            ->setStatus(200);
    }
}
