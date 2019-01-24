<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3.1.19
 * Time: 13.26
 */


return [
    // take from SiteConfig -> https://github.com/MIR24/mir24-mobile-api/blob/maven/src/main/java/ru/mirtv/m24api/objects/SiteConfig.java
    "imageBaseURL" => "http://api.mir24.tv/v2/media/images/uploaded/",
    "videoBaseURL" => "http://api.mir24.tv/v2/video/content/",

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
//TODO add actual config into database
    "streamURLAndroid"=>"http://api.mir24.tv/v2/media/images/uploaded/"


];