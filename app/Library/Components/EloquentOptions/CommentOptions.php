<?php

namespace App\Library\Components\EloquentOptions;


class CommentOptions
{
    private $page = 1;
    private $limit = 10;
    private $entityID;
    private $type = 0;  // news, photos, videos - к чему комментарий


    public function initFromArray(array $options): CommentOptions
    {
        foreach ($options as $key => $value) {
            $methodName = "set" . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this;
    }

    public function getCalculatedOffset()
    {
        return $this->getLimit() * ($this->getPage() - 1);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): CommentOptions
    {
        $this->page = $page;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): CommentOptions
    {
        $this->limit = $limit;
        return $this;
    }

    public function getEntityID()
    {
        return $this->entityID;
    }

    public function setEntityID($entityID)
    {
        $this->entityID = $entityID;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): CommentOptions
    {
        $this->type = $type;
        return $this;
    }

}
