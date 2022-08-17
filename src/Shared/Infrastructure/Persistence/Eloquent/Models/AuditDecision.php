<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditDecision extends Model
{
    protected $table = "audit_decisions";
    protected $guarded = [];
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;



}
