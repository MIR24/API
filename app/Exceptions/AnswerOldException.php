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
    /**
     * AnswerOldException constructor.
     * @param $operation
     * @param string $message
     */
    public function __construct($operation, $message = 'Unknown answer.')
    {
        parent::__construct($message);

        $this->newStatus = 400;

        $this->operation = $operation;
    }
}