<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 10.11
 */

namespace App\Exceptions;

use Exception;

class OldException extends Exception implements OldExceptionInterface
{
    protected $operation;

    protected $newStatus;

    function getResponseData()
    {
        return [
            "answer" => $this->operation,
            "status" => $this->newStatus,
            "message" => $this->message,
            "content" => null
        ];
    }
}