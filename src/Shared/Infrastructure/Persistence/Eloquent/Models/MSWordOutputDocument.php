<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSWordOutputDocument extends Model
{
    protected $table = "ms_word_output_documents";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
