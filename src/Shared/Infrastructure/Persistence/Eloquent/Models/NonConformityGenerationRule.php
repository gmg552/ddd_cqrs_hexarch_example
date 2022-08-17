<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonConformityGenerationRule extends Model
{
    protected $table = "non_conformities_generation_rules";
    protected $guarded = []; //Se mete para poder hacer el create []
    protected $casts = [
        'condition' => 'array',
    ];

    use softDeletes;
    use UuidTrait;

    public function checklistVersion() {
        return $this->belongsTo(ChecklistVersion::class, 'checkpoint_non_conformities', 'non_conformity_id', 'checkpoint_id');
    }

    public function auditedItems() {
        return $this->belongsToMany(AuditedItem::class, 'audited_item_non_conformities', 'non_conformity_id', 'audited_item_id');
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class);
    }

    public function nonConformityCategory() {
        return $this->belongsTo(NonConformityCategory::class);
    }

}
