<?php

namespace App\Library\Services;


class Transliterator
{
    public function toUrl(string $source)
    {
        $translit = "Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();";
        $out = strtolower(transliterator_transliterate($translit, $source));
//        $out = strtolower(transliterator_transliterate('Latin-ASCII',
//            transliterator_transliterate('Latin', $source)));
        $out = preg_replace('/[-\s]+/', '-', $out);
        return trim($out, '-');
    }
}
