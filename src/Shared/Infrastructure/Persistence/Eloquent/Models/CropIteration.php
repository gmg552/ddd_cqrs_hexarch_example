<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CropIteration extends Model
{
    protected $table = "crop_iterations";
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;


}
