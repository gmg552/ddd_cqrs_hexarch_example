<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistAuditedItemTimestamp extends Model
{
    protected $table = "checklist_audited_item_timestamps";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function checklistAuditedItem() {
        return $this->belongsTo(ChecklistAuditedItem::class);
    }

}
