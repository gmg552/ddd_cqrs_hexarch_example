<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditTraceabilityRecord extends Model
{
    protected $table = "audit_traceability_records";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function auditedItem() {
        return $this->belongsTo(AuditedItem::class);
    }

    public function audit() {
        return $this->belongsTo(Audit::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

}
