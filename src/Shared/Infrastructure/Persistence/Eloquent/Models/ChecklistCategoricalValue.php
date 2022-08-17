<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistCategoricalValue extends Model
{
    protected $table = "checklist_categorical_values";
    use softDeletes;
    use UuidTrait;
}
