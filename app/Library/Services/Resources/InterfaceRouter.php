<?php


namespace App\Library\Services\Resources;


interface InterfaceRouter
{
    function getResult(array $params):string;
}