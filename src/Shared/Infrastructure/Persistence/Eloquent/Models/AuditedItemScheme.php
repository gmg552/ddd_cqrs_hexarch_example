<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedItemScheme extends EloquentEntity
{
    protected $table = "audited_item_schemes";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function auditedScheme() {
        return $this->belongsTo(AuditedScheme::class);
    }

}
