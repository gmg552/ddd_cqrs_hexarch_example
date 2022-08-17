<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeAuditType extends Model
{
    protected $table = "scheme_audit_types";
    use softDeletes;
    use UuidTrait;

}
