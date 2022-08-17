<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    protected $table = "entities";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function fields()
    {
        return $this->hasMany(EntityField::class);
    }

}
