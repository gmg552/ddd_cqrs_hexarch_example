<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivacyLevel extends Model
{
    protected $table = "privacy_levels";
    use UuidTrait;
    use softDeletes;

}
