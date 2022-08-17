<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    protected $table = "certificates";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
