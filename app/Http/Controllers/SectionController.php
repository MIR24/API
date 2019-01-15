<?php

namespace App\Http\Controllers;


class SectionController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/channels/{channelId}/sections",
     *   summary="Получить список секций",
     *   description="Под секцией понимается категория или пункт меню в приложении.
             Например, 'Телешоу', 'Недавно в эфире'.
             Секция может содержать либо шоу, либо эпизоды.
             В зависимости от этого, в массиве links передаётся ссылка, либо на эпизоды, либо на шоу.
         ",
     *   tags={"SmartTV. Sections"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Cписок секций",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SectionCollection")),
     *   ),
     * )
     */
    public function cgetAction($channelId)
    {
        # TODO
    }

    /**
     * @OA\Get(
     *   path="/smart/v1/sections/{sectionId}",
     *   summary="Получить описание секции",
     *   tags={"SmartTV. Sections"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Описание секции",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SectionDetail")),
     *   ),
     * )
     */
    public function getAction($sectionId)
    {
        # TODO Это не надо, просто для сравнения описания с предыдущим методов
    }
}
