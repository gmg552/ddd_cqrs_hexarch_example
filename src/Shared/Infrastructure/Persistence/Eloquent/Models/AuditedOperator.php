<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedOperator extends Model
{
    protected $table = "audited_operators";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function operator() {
        return $this->hasOne(Operator::class, 'id', 'operator_id');
    }

    public function auditedItem() {
        return $this->hasOne(AuditedItem::class, 'id', 'id');
    }

    public function productionUnits() {
        return $this->belongsToMany(ProductionUnit::class, 'audited_operator_production_units', 'audited_operator_id', 'production_unit_id');
    }

    public function auditedSchemes() {
        return $this->belongsToMany(AuditedScheme::class, 'audited_item_schemes', 'audited_item_id', 'audited_scheme_id');
    }

}
