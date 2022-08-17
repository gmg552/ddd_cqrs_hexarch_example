<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    protected $table = "subjects";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function legalRepresentative()
    {
        return $this->belongsTo(Subject::class, 'legal_representative_id');
    }

    public function auditor()
    {
        return $this->hasOne(Auditor::class, 'id', 'id');
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

}
