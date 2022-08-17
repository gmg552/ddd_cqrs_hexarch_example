<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheme extends Model
{
    protected $table = "schemes";
    protected $guarded = [];
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;

    public function auditTypes() {
        return $this->belongsToMany(AuditType::class, 'scheme_audit_types', 'scheme_id', 'audit_type_id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'id', 'id');
    }

    public function nonConformityCodeRange() {
        return $this->belongsTo(NonConformityCodeRange::class);
    }

    public function auditCodeRange() {
        return $this->belongsTo(AuditCodeRange::class);
    }

    public function operatorCBNumerationRange() {
        return $this->belongsTo(OperatorCBNumerationRange::class, 'operator_cb_numeration_range_id');
    }

    public function versions(){
        return $this->hasMany(SchemeVersion::class);
    }

}
