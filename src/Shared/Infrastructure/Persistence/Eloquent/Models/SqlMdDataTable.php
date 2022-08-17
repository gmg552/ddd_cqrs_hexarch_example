<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SqlMdDataTable extends Model
{
    protected $table = "sql_md_data_tables";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
