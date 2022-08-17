<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditOutputDocument extends Model
{
    protected $table = "audit_output_documents";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function outputDocument() {
        return $this->belongsTo(OutputDocument::class, 'id', 'id');
    }

}
