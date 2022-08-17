<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateType extends Model
{
    protected $table = "certificate_types";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
