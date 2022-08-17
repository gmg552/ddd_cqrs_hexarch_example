<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IterationSamplingCategory extends Model
{
    protected $table = "iteration_sampling_categories";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function auditType() {
        return $this->belongsTo(AuditType::class);
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class);
    }

}
