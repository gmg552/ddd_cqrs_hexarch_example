<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Iteration extends Model
{
    protected $table = "iterations";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


    public function orderedScheme(){
        return $this->belongsTo(OrderedScheme::class, 'ordered_scheme_id');
    }

    public function scheme(){
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

}
