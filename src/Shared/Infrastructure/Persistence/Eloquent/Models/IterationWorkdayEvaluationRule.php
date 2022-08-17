<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IterationWorkdayEvaluationRule extends Model
{
    protected $table = "iteration_workday_evaluation_rules";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function iterationSamplingCategory() {
        return $this->belongsTo(IterationSamplingCategory::class);
    }
}
