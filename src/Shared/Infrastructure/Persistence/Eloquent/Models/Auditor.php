<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auditor extends Model
{
    protected $table = "auditors";
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;

    public function subject()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Subject', 'id', 'id');
    }

    public function activeSchemes() {
        return $this->belongsToMany('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme', 'auditor_schemes')
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->where(function ($query) {
                $query->whereDate('end_date', '>=', date('Y-m-d'))
                    ->orWhereNull('end_date');
            });
    }

    public function schemes() {
        return $this->belongsToMany('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme', 'auditor_schemes')
            ->withPivot('start_date', 'end_date');
    }

    public function auditTeamMembers() {
        return $this->hasMany(AuditTeamMember::class);
    }

}
