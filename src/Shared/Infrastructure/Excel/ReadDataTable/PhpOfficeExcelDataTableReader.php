<?php

namespace Qalis\Shared\Infrastructure\Excel\ReadDataTable;

use RuntimeException;
use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Qalis\Shared\Domain\Excel\ReadDataTable\ExcelDataTableReader;

final class PhpOfficeExcelDataTableReader implements ExcelDataTableReader {

    public function __invoke(string $filePath, string $sheetName, int $firstRow, array $columnIndexes): array
    {
        $reader = $this->getReader('Xlsx', $sheetName);
        $spreadsheet = $this->getSpreadsheet($reader, $filePath);
        $this->ensureSheetNameExist($spreadsheet, $sheetName);
        $arrayData = $this->getArrayDataFromActiveSheet($spreadsheet);

        return $this->getFormattedDataFromArrayData($arrayData, $firstRow, $columnIndexes);
    }

    private function ensureSheetNameExist($spreadsheet, $sheetName) {
        if (!$spreadsheet->getSheetByName($sheetName)) {
            throw new RuntimeException("La hoja <$sheetName> no existe en el archivo excel cargado.");
        }
    }

    private function getFormattedDataFromArrayData(array $arrayData, int $firstRow, array $columnIndexes): array {
        $array = [];
        foreach ($arrayData as $indexArrayData => $dataValues) {
            if ($indexArrayData >= ($firstRow-1)) {
                $arrayTemp = [];
                foreach($columnIndexes as $code => $indexValue) {
                    if (!isset($dataValues[$indexValue-1])) {
//                        $indexArrayData++;
//                        throw new RuntimeException("El índice <$indexValue> no se ha encontrado en la fila <".$indexArrayData.">");
                        $arrayTemp[$code] = '';
                    }
                    $arrayTemp[$code] = $dataValues[$indexValue-1];
                }
                array_push($array, $arrayTemp);
            }
        }
        return $array;
    }

    private function getArrayDataFromActiveSheet($spreadsheet): array {
        return $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
    }

    private function getSpreadsheet($reader, $filePath) {
//        if (!Storage::disk('local')->exists($filePath)) {
//            throw new InvalidArgumentException("El archivo <$filePath> no existe en la carpeta storage/app");
//        }
        return $reader->load($filePath);
    }

    private function getReader($readerType, $sheetName) {
        $reader = IOFactory::createReader($readerType);
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly($sheetName);
        return $reader;
    }

}
