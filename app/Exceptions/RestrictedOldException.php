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
    /**
     * RestrictedOldException constructor.
     * @param $operation
     * @param string $message
     */
    public function __construct($operation, $message = 'RESTRICTED')
    {
        parent::__construct($message);

        $this->newStatus = 403;

        $this->operation = $operation;
    }
}