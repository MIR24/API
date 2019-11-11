<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="ChanelTv",
 *   type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="iosLink", type="string"),
 *      @OA\Property(property="androidLink", type="string"),
 *       @OA\Property(property="logo", type="string"),
 * )
 */
class Chanels extends Model
{
    protected $fillable = ['name','iosLink','androidLink','logo'];
}
