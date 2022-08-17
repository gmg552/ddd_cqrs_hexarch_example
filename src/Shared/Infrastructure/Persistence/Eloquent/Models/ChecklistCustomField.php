<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistCustomField extends Model
{
    protected $table = "checklist_custom_fields";
    use softDeletes;
    use UuidTrait;

    public function version() {
        return $this->belongsTo(ChecklistVersion::class, 'checklist_version_id');
    }

    public function values() {
        return $this->hasMany(ChecklistCustomFieldValue::class, 'checklist_custom_field_id');
    }


}
