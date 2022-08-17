<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorCBNumerationRange extends Model
{
    protected $table = "operator_cb_numeration_ranges";
    use softDeletes;
    use UuidTrait;


}
