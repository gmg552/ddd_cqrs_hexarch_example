<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class Uuid2Id
{

    /**
    * Recibe un uuid y obtiene el id correspondiente en la tabla
    */
    public static function resolve(string $table, string $uuid): int
    {
        $item = DB::table($table)->where('uuid',  hex2bin($uuid))->first();
        if (!$item) throw new RuntimeException("No existe el registro con uuid <$uuid> en la tabla <$table>");
        return $item->id;
    }
}
