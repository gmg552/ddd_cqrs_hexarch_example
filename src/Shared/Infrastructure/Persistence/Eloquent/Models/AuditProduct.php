<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditProduct extends Model
{
    protected $table = "audit_products";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function audit() {
        return $this->belongsTo(Audit::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

}
