<?php

namespace Qalis\Shared\Infrastructure\Legacy\SpreadsheetOutputDocuments;

use App\Models\Common\OutputDocument;
use App\Models\Common\SpreadsheetOutputDocument;
use App\Models\Common\SqlSpreadsheetDataTable;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SpreadsheetOutputDocumentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new SpreadsheetOutputDocument());
    }

    /**
     * @param $uuid
     * Uuid asociado a OutputDocument
     */
    public function getForPrinting($uuid)  {

        $od = OutputDocument::with('spreadsheetDocument.sqlSpreadsheetAreas.masterTable')
        ->whereUuid($uuid)
        ->first();

        foreach($od->spreadsheetDocument->sqlSpreadsheetAreas as $area) {
            $mt = clone $area->masterTable;
            $mt->sqlSpreadsheetDataTable = (object) SqlSpreadsheetDataTable::with('headerColumns')->with('footerColumns')->where('sql_spreadsheet_area_id', $area->id)
                ->where('sql_md_data_table_id', $area->masterTable->id)
                ->first();
            $area->masterTable = $mt;
        }

        return $od;

    }


}
