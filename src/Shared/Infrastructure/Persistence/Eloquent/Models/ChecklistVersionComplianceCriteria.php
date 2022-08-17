<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistVersionComplianceCriteria extends Model
{
    protected $table = "checklist_version_compliance_criterias";
    use softDeletes;
    use UuidTrait;
}
