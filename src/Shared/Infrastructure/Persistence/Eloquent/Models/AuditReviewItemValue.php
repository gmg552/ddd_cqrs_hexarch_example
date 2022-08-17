<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditReviewItemValue extends Model
{
    protected $table = "audit_review_item_values";
    protected $guarded = [];
    use softDeletes;

}
