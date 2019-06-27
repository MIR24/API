<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 17.54
 */

namespace App\Exceptions;


class RestrictedOldException extends OldException
{
    public function __construct(string $operation, string $message = 'RESTRICTED')
    {
        parent::__construct($operation, $message);

        $this->newStatus = 400;
    }
}