<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait UuidTrait {

    public static function bootUuidTrait() {
        self::retrieved(function ($model) {
            $model->uuid = bin2hex($model->uuid);
        });
        self::updating(function ($model) {
            $model->uuid = hex2bin($model->uuid);
        });
        self::updated(function ($model) {
            $model->uuid = bin2hex($model->uuid);
        });
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $uuid
     * @param  string  $uuidColumn
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereUuid($query, $uuid): Builder
    {
        $uuid = array_map(function ($uuid) {
            return hex2bin(Str::lower($uuid));
        }, Arr::wrap($uuid));

        return $query->whereIn('uuid', $uuid);
    }


}
