<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3.1.19
 * Time: 13.26
 */


return [

    'image_root'=>'https://mir24.tv',
    'video_root'=>'https://mirtvpc.cdnvideo.ru/mirtv',
    // take from SiteConfig -> https://github.com/MIR24/mir24-mobile-api/blob/maven/src/main/java/ru/mirtv/m24api/objects/SiteConfig.java

    "imageBaseURL" => env('APP_URL','http://api.mir24.tv')."/images/uploaded/",
    "videoBaseURL" => env('APP_URL','http://api.mir24.tv')."/video/content/",

    // take from https://github.com/MIR24/mir24-mobile-api/blob/maven/src/main/webapp/WEB-INF/images.properties
    // also see InfoGetter::getSiteConfig  https://github.com/MIR24/mir24-mobile-api/blob/maven/src/main/java/ru/mirtv/m24api/getters/InfoGetter.java
    "imageTypes" => [
        [
            "alias" => "in_promo",
            "size" => "600x445"
        ],
        [
            "alias" => "rubric_main",
            "size" => "870x489"
        ],
        [
            "alias" => "rubric_list",
            "size" => "870x489"
        ],
        [
            "alias" => "inner",
            "size" => "600x445"
        ]

    ],

    "streamURLAndroid"=>"https://hls-mirtv.cdnvideo.ru/mirtv-parampublish/smil:mir24.smil/playlist.m3u8",

    "streamURLIOS"=>"https://hls-mirtv.cdnvideo.ru/mirtv-parampublish/smil:mir24.smil/playlist.m3u8",

];