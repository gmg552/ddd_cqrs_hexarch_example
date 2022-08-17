<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditReviewItem extends Model
{
    protected $table = "audit_review_items";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;



}
