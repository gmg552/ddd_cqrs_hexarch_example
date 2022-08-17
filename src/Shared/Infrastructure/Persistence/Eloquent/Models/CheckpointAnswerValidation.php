<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointAnswerValidation extends Model
{
    protected $table = "checkpoint_answer_validations";
    protected $casts = [
        'premise' => 'array',
        'conclusion' => 'array'
    ];
    use softDeletes;
    use UuidTrait;
}
