<?php

namespace App\Library\Components\EloquentOptions;


class NewsOption
{
    public const LIMIT_DEFAULT = 10;

    private $page = 1;
    private $limit = self::LIMIT_DEFAULT;
    private $category = null;
    private $newsID = null;
    private $actual = false;
    private $onlyVideo = false;
    private $onlyWithGallery = false;
    private $lastNews = false;
    private $tags = null;
    private $countryID = null;
    private $preSearch = null;
    private $ignoreId;
//    private $buffer = null;

//    public Boolean checkBufferForID(Integer id) {
//        Boolean exists = false;
//        if (buffer.contains(id)) {
//            exists = Boolean.TRUE;
//        }
//        return exists;
//    }
//
//    public void saveToBuffer(Integer id) {
//        buffer.add(id);
//    }

    public function initFromArray(array $options): NewsOption
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

    public function setPage(int $page): NewsOption
    {
        $this->page = $page;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): NewsOption
    {
        $this->limit = $limit;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getNewsID()
    {
        return $this->newsID;
    }

    public function setNewsID($newsID)
    {
        $this->newsID = $newsID;
        return $this;
    }

    public function isActual(): bool
    {
        return $this->actual;
    }

    public function setActual(bool $actual): NewsOption
    {
        $this->actual = $actual;
        return $this;
    }

    public function isOnlyVideo(): bool
    {
        return $this->onlyVideo;
    }

    public function setOnlyVideo(bool $onlyVideo): NewsOption
    {
        $this->onlyVideo = $onlyVideo;
        return $this;
    }

    public function isOnlyWithGallery(): bool
    {
        return $this->onlyWithGallery;
    }

    public function setOnlyWithGallery(bool $onlyWithGallery): NewsOption
    {
        $this->onlyWithGallery = $onlyWithGallery;
        return $this;
    }

    public function isLastNews(): bool
    {
        return $this->lastNews;
    }

    public function setLastNews(bool $lastNews): NewsOption
    {
        $this->lastNews = $lastNews;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getCountryID()
    {
        return $this->countryID;
    }

    public function setCountryID($countryID)
    {
        $this->countryID = $countryID;
        return $this;
    }

    public function getPreSearch()
    {
        return $this->preSearch;
    }

    public function setPreSearch($preSearch)
    {
        $this->preSearch = $preSearch;
        return $this;
    }

    public function getIgnoreId()
    {
        return $this->ignoreId;
    }

    public function setIgnoreId($ignoreId)
    {
        $this->ignoreId = $ignoreId;
        return $this;
    }
}
