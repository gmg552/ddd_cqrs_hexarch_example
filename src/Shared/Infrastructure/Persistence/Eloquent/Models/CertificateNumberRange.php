<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateNumberRange extends Model
{
    protected $table = "certificate_number_ranges";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
