<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedScheme extends Model
{
    protected $table = "audited_schemes";
    use softDeletes;
    use UuidTrait;

    public function nonConformityCodeRange() {
        return $this->hasOneThrough(NonConformityCodeRange::class, Scheme::class);
    }

    public function audit() {
        return $this->belongsTo(Audit::class);
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class);
    }

    public function orderedScheme() {
        return $this->belongsTo(OrderedScheme::class);
    }

    public function cropIteration() {
        return $this->belongsTo(CropIteration::class);
    }

    public function operatorIteration() {
        return $this->belongsTo(OperatorIteration::class);
    }

    public function handlingUnitIteration() {
        return $this->belongsTo(HandlingUnitIteration::class);
    }

    public function croppedAreaIteration() {
        return $this->belongsTo(CroppedAreaIteration::class);
    }

}
