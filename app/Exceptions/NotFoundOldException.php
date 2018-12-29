<?php

namespace App\Exceptions;


class NotFoundOldException extends OldException
{
    public function __construct(string $operation, string $message = "NOT FOUND")
    {
        parent::__construct($operation, $message);

        $this->newStatus = 404;
    }
}
