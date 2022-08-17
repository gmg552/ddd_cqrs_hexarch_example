<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutputDocument extends Model
{
    protected $table = "output_documents";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
