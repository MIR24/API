<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 11.44
 */

namespace App\Library\Services;


class ResultTokenOfCommand extends ResultOfCommand
{

    private $token;

    public function getAsArray()
    {
        return [
            'answer' => $this->getOperation(),
            'status' => $this->getStatus() ?? 500,
            'message' => $this->getMessage(),
            'token' => $this->getToken(),
        ];
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

}