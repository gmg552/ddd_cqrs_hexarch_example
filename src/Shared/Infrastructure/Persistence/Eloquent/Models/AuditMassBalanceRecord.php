<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditMassBalanceRecord extends Model
{
    protected $table = "audit_mass_balance_records";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function audit() {
        return $this->belongsTo(Audit::class);
    }

}
