<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 10.51
 */

namespace App\Exceptions;


class AnswerOldException extends OldException
{
    public function __construct(string $operation, string $message = 'Unknown answer.')
    {
        parent::__construct($operation, $message);

        $this->newStatus = 400;
    }
}