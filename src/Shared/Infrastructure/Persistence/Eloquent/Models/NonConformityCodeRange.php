<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonConformityCodeRange extends Model
{
    protected $table = "non_conformity_code_ranges";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

}
