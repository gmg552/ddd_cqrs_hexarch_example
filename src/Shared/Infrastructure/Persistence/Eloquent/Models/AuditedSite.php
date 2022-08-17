<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedSite extends Model
{
    protected $table = "audited_items";
    use softDeletes;
    use UuidTrait;

    public function auditor(){
        return $this->belongsTo(Auditor::class, 'real_auditor_id');
    }

    public function strawAuditor(){
        return $this->belongsTo(Auditor::class, 'straw_auditor_id');
    }

    public function siteAnswers() {
        return $this->hasMany(CheckpointSiteAnswer::class, 'audited_item_id');
    }

    public function customFieldValues(){
        return $this->hasMany(ChecklistCustomFieldValue::class, 'audited_site_id');
    }

}
