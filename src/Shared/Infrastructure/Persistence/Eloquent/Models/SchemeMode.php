<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeMode extends Model
{
    protected $table = "scheme_modes";
    use softDeletes;
    use UuidTrait;

}
