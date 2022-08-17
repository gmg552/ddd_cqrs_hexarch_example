<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedHandlingUnit extends Model
{
    protected $table = "audited_handling_units";
    protected  $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function handlingUnit() {
        return $this->hasOne(HandlingUnit::class, 'id', 'handling_unit_id');
    }

    public function auditedItem() {
        return $this->hasOne(AuditedItem::class, 'id', 'id');
    }

    public function auditedSchemes() {
        return $this->belongsToMany(AuditedScheme::class, 'audited_item_schemes', 'audited_item_id', 'audited_scheme_id');
    }

}
