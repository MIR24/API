<?php

namespace App\Library\Components;


class NewsTextConverter
{
    private $text = "";

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): NewsTextConverter
    {
        $this->text = $text;
        return $this;
    }

    public function cutGalleryTags()
    {
//        while (srcText.contains("lightbox")) {
//            String tmp = srcText.substring(srcText.indexOf("<a"), srcText.indexOf("/a>") + 3);
//            if (tmp.contains("lightbox")) {
//                distText = distText.replace(tmp, "");
//            }
//            srcText = srcText.replace(tmp, "");
//        }
//        distText = distText.replaceAll("<p></p>", "");
    }

    /**
     * Method replaces remote links like http://mir24.tv/news/../id to news://id
     */
    public function changeTextLinks()
    {
        return $this->setText(preg_replace(
            '|"https?://mir24.tv/news/(\d+)\S*"|',
            '"news://$1"',
            $this->getText()
        ));
    }
}
