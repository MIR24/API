<?php

namespace App\Library\Services;


class ResultOfCommand
{
    private $operation;
    private $status;
    private $message;
    private $content;

    public function getAsArray()
    {
        return [
            'answer' => $this->getOperation(),
            'status' => $this->getStatus() ?? 500,
            'message' => $this->getMessage(),
            'content' => $this->getContent(),
        ];
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}
