<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MSWordOutputDocumentDataTable extends Model
{
    protected $table = "ms_word_output_document_data_tables";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function outputDocument() {
        return $this->belongsTo(OutputDocument::class);
    }

    public function dataTable() {
        return $this->belongsTo(SqlMdDataTable::class);
    }

}
