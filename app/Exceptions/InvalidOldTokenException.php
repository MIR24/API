<?php

namespace App\Exceptions;


class InvalidOldTokenException extends OldException
{
    public function __construct(string $operation, string $message = 'CLIENT ERROR')
    {
        parent::__construct($operation, $message);

        $this->newStatus = 400;
    }
}
