<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonConformity extends Model
{
    protected $table = "non_conformities";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function checkpoints() {
        return $this->belongsToMany(ChecklistCheckpoint::class, 'checkpoint_non_conformities', 'non_conformity_id', 'checkpoint_id');
    }

    public function auditedItems() {
        return $this->belongsToMany(AuditedItem::class, 'audited_item_non_conformities', 'non_conformity_id', 'audited_item_id');
    }

    public function auditedScheme() {
        return $this->belongsTo(AuditedScheme::class);
    }

    public function category() {
        return $this->belongsTo(NonConformityCategory::class);
    }

}
