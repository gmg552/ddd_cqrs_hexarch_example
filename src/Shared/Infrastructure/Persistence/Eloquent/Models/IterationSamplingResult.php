<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IterationSamplingResult extends Model
{
    protected $table = "iteration_sampling_results";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function iterationSamplingCategory() {
        return $this->belongsTo(IterationSamplingCategory::class);
    }

    public function iteration() {
        return $this->belongsTo(Iteration::class);
    }

}
