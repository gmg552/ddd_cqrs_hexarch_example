<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTemplate extends Model
{
    protected $table = "checklist_templates";
    use UuidTrait;
    use softDeletes;

    public function checklistVersions() {
        return $this->hasMany(ChecklistVersion::class, 'template_id');
    }
}
