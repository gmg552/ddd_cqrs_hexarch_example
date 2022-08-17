<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistEncodedTerm extends Model
{
    protected $table = "checklist_encoded_terms";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;
}
