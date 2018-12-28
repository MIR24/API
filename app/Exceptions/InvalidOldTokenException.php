<?php

namespace App\Exceptions;


class InvalidOldTokenException extends OldException
{

    /**
     * InvalidOldTokenException constructor.
     * @param $operation
     */
    public function __construct($operation)
    {
        parent::__construct('CLIENT ERROR');

        $this->newStatus = 400;

        $this->operation = $operation;
    }
}
