<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedProductionUnit extends Model
{
    protected $table = "audited_production_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function productionUnit() {
        return $this->hasOne(ProductionUnit::class, 'id', 'production_unit_id');
    }

    public function auditedItem() {
        return $this->hasOne(AuditedItem::class, 'id', 'id');
    }

    public function auditedSchemes() {
        return $this->belongsToMany(AuditedScheme::class, 'audited_item_schemes', 'audited_item_id', 'audited_scheme_id');
    }

}
