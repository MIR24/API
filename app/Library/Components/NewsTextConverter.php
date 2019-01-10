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
        $textIn = $this->text;
        $textOut = "";

        while (strpos($textIn, "lightbox") !== false) {
            $aStartPos = strpos($textIn, "<a");
            $aEndPos = strpos($textIn, "/a>") + 3;

            if ($aStartPos === false || $aEndPos === false) {
                $textOut .= $textIn;
                break;
            }

            $tmpSubstr = substr($textIn, $aStartPos, $aEndPos - $aStartPos);
            if (strpos($tmpSubstr, "lightbox") !== false) {
                $textOut .= substr($textIn, 0, $aStartPos);
            } else {
                $textOut .= substr($textIn, 0, $aEndPos);
            }

            $textIn = substr($textIn, $aEndPos);
            $aStartPos = null;
            $aEndPos = null;
        }

        return $this->setText(str_replace("<p></p>", "", $textOut));
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
