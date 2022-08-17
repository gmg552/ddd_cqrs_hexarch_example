<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeFile extends Model
{
    protected $table = "scheme_files";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function operator()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Operator');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

}
