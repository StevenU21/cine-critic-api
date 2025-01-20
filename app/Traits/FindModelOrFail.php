<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotFoundException;

trait FindModelOrFail
{
    public static function findOrFailCustom($id): Model
    {
        $model = static::find($id);

        if (!$model) {
            throw new NotFoundException();
        }

        return $model;
    }
}
