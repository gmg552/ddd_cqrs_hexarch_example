<?php

namespace Qalis\Shared\Infrastructure\Legacy\MSExcelSpreadsheets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;


class MSExcelSpreadsheetHelper {

    protected $excelFile = null;

    public function __construct($file) {
        $this->excelFile = IOFactory::load(Storage::disk('local')->path($file));

        $this->extension = explode(".", $file)[1];
    }

    public function printDocument($spreadsheetOutputDocument) {

        foreach($spreadsheetOutputDocument->sqlSpreadsheetAreas as $sqlSpreadsheetArea) {

            if (!$sqlSpreadsheetArea->masterTable->sqlSpreadsheetDataTable) {
                $sqlSpreadsheetArea->masterTable->sqlSpreadsheetDataTable =
                    $sqlSpreadsheetArea->masterTable->sqlSpreadsheetDataTables->where('sql_spreadsheet_area_id', $sqlSpreadsheetArea->id)->first();
            }

            $printheadPosition = [];
            if ($sqlSpreadsheetArea->transponsed) {
                $printheadPosition['row'] = $sqlSpreadsheetArea->masterTable->sqlSpreadsheetDataTable->first_row_index;
                $printheadPosition['col'] = $sqlSpreadsheetArea->first_col_index;
            } else {
                $printheadPosition['row'] = $sqlSpreadsheetArea->first_row_index;
                $printheadPosition['col'] = $sqlSpreadsheetArea->masterTable->sqlSpreadsheetDataTable->first_col_index;
            }

            $this->printSqlSpreadsheetArea($sqlSpreadsheetArea->id, $sqlSpreadsheetArea->masterTable->rows, $printheadPosition, $sqlSpreadsheetArea->transponsed, $sqlSpreadsheetArea->spreadsheetOutputDocumentArea->sheet_name);
        }

    }

    public function nextPrintheadPosition($currentPrintheadPosition, $offset, $sqlSpreadsheetDataTable, $transponsed) {

        $newPrintheadPosition = [];
        if ($transponsed) {
            $newPrintheadPosition['col'] = $this->_getCol($currentPrintheadPosition['col'], $offset);
            $newPrintheadPosition['row'] = $sqlSpreadsheetDataTable->first_row_index;
        } else {
            $newPrintheadPosition['row'] = $currentPrintheadPosition['row'] + $offset;
            $newPrintheadPosition['col'] = $sqlSpreadsheetDataTable->first_col_index;
        }

        return $newPrintheadPosition;
    }


    public function printSqlSpreadsheetArea($areaId, $currentRows, $printheadPosition, $transponsed, $sheetName) {

        foreach($currentRows as $dataRow) {

            if (!$dataRow->sqlMDDataTable->sqlSpreadsheetDataTable) {
                $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable =
                    $dataRow->sqlMDDataTable->sqlSpreadsheetDataTables->where('sql_spreadsheet_area_id', $areaId)->first();
            }

            $this->printRow($dataRow, $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable->headerColumns, $printheadPosition, $transponsed, $sheetName);
            if ((isset($dataRow->detailRows)) && (count((array)$dataRow->detailRows) > 0)) {
				$printheadPosition = $this->nextPrintheadPosition(
					$printheadPosition, 
					$dataRow->sqlMDDataTable->sqlSpreadsheetDataTable->offset, 
					$dataRow->sqlMDDataTable->detailTable->sqlSpreadsheetDataTables->where('sql_spreadsheet_area_id', $areaId)->first(), 
					$transponsed
				);
                $printheadPosition = $this->printSqlSpreadsheetArea($areaId, $dataRow->detailRows, $printheadPosition, $transponsed, $sheetName);
                if ($dataRow->sqlMDDataTable->sqlSpreadsheetDataTable->footerColumns->isNotEmpty()) {
                    $printheadPosition = $this->nextPrintheadPosition($printheadPosition, 1, $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable, $transponsed);
                    $this->printRow($dataRow, $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable->footerColumns, $printheadPosition, $transponsed, $sheetName);
                }
			}
			//avanzamos tantas filas o columnas como indique offset salvo si es la última fila de datos
            $cp = array_values((array) $currentRows);
            if ($dataRow != $cp[count($cp) - 1])
                $printheadPosition = $this->nextPrintheadPosition($printheadPosition, $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable->offset, $dataRow->sqlMDDataTable->sqlSpreadsheetDataTable, $transponsed);
            

        }

        return $printheadPosition;

    }


    public function printRow($row, $columns, $printheadPosition, $transponsed, $sheetName) {

        if ($transponsed) {
            foreach($columns as $column) {
                if (in_array($column->name, array_keys((array)$row))) {
                    $this->excelFile->getSheetByName($sheetName)->setCellValue($this->_getPositionFromRowCol($printheadPosition['row'] + $column->offset, $printheadPosition['col']), $row->{$column->name});
                }
            }
            $printheadPosition['row'] =  $printheadPosition['row'] + $columns->max('offset');

        } else {
            foreach($columns as $column) {
                if (in_array($column->name, array_keys((array)$row))) {
                    $this->excelFile->getSheetByName($sheetName)->setCellValue($this->_getPositionFromRowCol($printheadPosition['row'], $this->_getCol($printheadPosition['col'], $column->offset)), $row->{$column->name});
                }
            }
            $printheadPosition['col'] = $this->_getCol($printheadPosition['col'], $columns->max('offset'));
        }

    }

    public function downloadFile() {
        $excelFile = $this->excelFile;
        $response =  new StreamedResponse(
            function () use ($excelFile) {
                $writer = new Xlsx($excelFile);
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan_'.date("dmyhis").'.'.$this->extension.'"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }

    public function saveFile($path) {
        $writer = new Xlsx($this->excelFile);
        $writer->save($path);
    }

    private function _getPositionFromRowCol($row, $col) {
        return (string) $col . $row;
    }

    private function _getCol($col, $offset) {
        return Coordinate::stringFromColumnIndex(Coordinate::columnIndexFromString($col) + $offset);
    }

}
