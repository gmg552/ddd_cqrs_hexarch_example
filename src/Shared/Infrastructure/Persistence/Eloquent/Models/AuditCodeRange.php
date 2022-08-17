<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditCodeRange extends Model
{
    protected $table = "audit_code_ranges";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;



}
