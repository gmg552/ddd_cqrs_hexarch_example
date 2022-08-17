<?php

namespace Qalis\Shared\Domain\MsWordOutputDocuments\Printer;

use Qalis\Certification\MSWordOutputDocumentDataTables\Domain\MSWordOutputDocumentDataTableReadRepository;
use Qalis\Certification\MSWordOutputDocumentDataTables\Domain\Search\MSWordOutputDocumentDataTableResponse;
use Qalis\Certification\MSWordOutputDocuments\Domain\MSWordOutputDocumentReadRepository;
use Qalis\Certification\Shared\Domain\OutputDocuments\OutputDocumentId;
use Qalis\Certification\SqlMdDataTables\Domain\SqlMdDataTableReadRepository;
use Qalis\Shared\Domain\DownloadableDocumentResponse;
use Qalis\Shared\Domain\Storage\StorageManager;
use Qalis\Shared\Infrastructure\Printer\PhpWordPrinter\PhpWordPrinter;

class MSWordOutputDocumentPrinter {

    private MSWordOutputDocumentReadRepository $msWordOutputDocumentReadRepository;
    private SqlMdDataTableReadRepository $sqlMdDataTableReadRepository;
    private MSWordOutputDocumentDataTableReadRepository $msWordOutputDocumentDataTableReadRepository;
    private StorageManager $storageManager;

    public function __construct(
        MSWordOutputDocumentReadRepository $msWordOutputDocumentReadRepository,
        SqlMdDataTableReadRepository $sqlMdDataTableReadRepository,
        MSWordOutputDocumentDataTableReadRepository $msWordOutputDocumentDataTableReadRepository,
        StorageManager $storageManager
    ) {
        $this->msWordOutputDocumentReadRepository = $msWordOutputDocumentReadRepository;
        $this->sqlMdDataTableReadRepository = $sqlMdDataTableReadRepository;
        $this->msWordOutputDocumentDataTableReadRepository = $msWordOutputDocumentDataTableReadRepository;
        $this->storageManager = $storageManager;
    }

    public function __invoke(OutputDocumentId $outputDocumentId, string $outputFileName, array $params) : DownloadableDocumentResponse
    {

        $dataTableIds = $this->msWordOutputDocumentReadRepository->searchSqlMdDatableIds($outputDocumentId);
        $filledDataTables = $this->sqlMdDataTableReadRepository->fill($dataTableIds, $params);
        $msWordDataTables = $this->msWordOutputDocumentDataTableReadRepository->searchByOutputDocument($outputDocumentId);
        $msOutputDocument = $this->msWordOutputDocumentReadRepository->find($outputDocumentId);
        $wordDocument = $this->storageManager->msWordTemplatePath($msOutputDocument->fileName());

        $wordPrinter = new PhpWordPrinter($wordDocument);

        foreach($msWordDataTables as $msWordDataTable) {
            $selectedFilledDataTable = $this->searchFilledDataTables($filledDataTables, $msWordDataTable->sqlMDDataTableId());
            $data = $selectedFilledDataTable->rows();
            if ($msWordDataTable->format() == MSWordOutputDocumentDataTableResponse::SINGLE_FORMAT_TYPE) {
                for($i=0; $i<$selectedFilledDataTable->rowsCount(); $i++) {
                    $wordPrinter->printSingleDataArray($data[$i]);
                }
            } else if ($msWordDataTable->format() == MSWordOutputDocumentDataTableResponse::TABLE_FORMAT_TYPE) {
                if ((count($data) == 0) && ($msWordDataTable->hideIfNoRows())) {
                    $wordPrinter->deleteBlockByReferenceParam($msWordDataTable->rowReferenceParam());
                } else {
                    $wordPrinter->cloneTableRow($msWordDataTable->rowReferenceParam(), $data);
                }
            } else if ($msWordDataTable->format() == MSWordOutputDocumentDataTableResponse::BLOCK_FORMAT_TYPE) {
                $wordPrinter->cloneBlock($msWordDataTable->code(), $data);
            }
        }

        return new DownloadableDocumentResponse(
            $wordPrinter->getBinaryContent(),
            $outputFileName.$wordPrinter->extension()
        );

    }

    private function searchFilledDataTables($filledDataTables, $id) {
        foreach($filledDataTables as $filledDataTable) {
            if ($filledDataTable->id() == $id) return $filledDataTable;
        }
        return null;
    }

}
