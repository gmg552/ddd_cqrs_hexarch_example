<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonConformityCategory extends Model
{
    protected $table = "non_conformity_categories";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

}
