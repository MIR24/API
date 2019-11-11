<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="ChoiceTv",
 *   type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="category", type="string"),
 * )
 */

class ChoiceCategory extends Model
{
    protected $fillable = ['name','category'];
}
