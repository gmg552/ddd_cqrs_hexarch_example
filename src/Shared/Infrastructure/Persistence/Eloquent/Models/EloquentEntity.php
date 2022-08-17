<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Qalis\Shared\Domain\Utils\StringUtils;

class EloquentEntity extends Model
{

    public static function fromArray(array $propertyArray) : self {
        $model = new static();
        foreach ($propertyArray as $key => $value) {
            $model->{StringUtils::toSnakeCase($key)} = $value;
        }
        $model->uuid = hex2bin($propertyArray['id']);
        $model->id = null;
        return $model;
    }


}
