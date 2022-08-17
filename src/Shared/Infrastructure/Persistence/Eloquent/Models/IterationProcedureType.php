<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IterationProcedureType extends Model
{
    protected $table = "iteration_procedure_types";
    use softDeletes;
    use UuidTrait;



}
