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
    /**
     * ServerOldException constructor.
     * @param $operation
     * @param string $message
     */
    public function __construct($operation, $message = 'SERVER ERROR')
    {
        parent::__construct($message);

        $this->newStatus = 502;

        $this->operation = $operation;
    }
}