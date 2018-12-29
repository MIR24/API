<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 10.31
 */

namespace App\Exceptions;


class ServerOldException extends OldException
{
    public function __construct(string $operation, string $message = "SERVER ERROR")
    {
        parent::__construct($operation, $message);

        $this->newStatus = 502;
    }
}