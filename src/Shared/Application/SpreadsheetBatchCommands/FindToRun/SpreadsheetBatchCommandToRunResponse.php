<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\FindToRun;

final class SpreadsheetBatchCommandToRunResponse {

    private string $batchCommandId;
    private string $sheetName;
    private int $firstRow;
    private array $columnIndexes;
    private string $protocol;
    private string $code;
    private array $paramMatching;
    private array $customFieldParamMatching;

    public function __construct(
        string $batchCommandId,
        string $sheetName,
        int $firstRow,
        array $columnIndexes,
        string $protocol,
        string $code,
        array $paramMatching,
        array $customFieldParamMatching
    )
    {
        $this->sheetName = $sheetName;
        $this->firstRow = $firstRow;
        $this->columnIndexes = $columnIndexes;
        $this->protocol = $protocol;
        $this->code = $code;
        $this->paramMatching = $paramMatching;
        $this->batchCommandId = $batchCommandId;
        $this->customFieldParamMatching = $customFieldParamMatching;
    }

    /**
     * @return string
     */
    public function sheetName(): string
    {
        return $this->sheetName;
    }

    /**
     * @return int
     */
    public function firstRow(): int
    {
        return $this->firstRow;
    }

    /**
     * @return array
     */
    public function columnIndexes(): array
    {
        return $this->columnIndexes;
    }

    /**
     * @return string
     */
    public function protocol(): string
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function paramMatching(): array
    {
        return $this->paramMatching;
    }

    /**
     * @return string
     */
    public function batchCommandId(): string
    {
        return $this->batchCommandId;
    }

    /**
     * @return array
     */
    public function customFieldParamMatching(): array
    {
        return $this->customFieldParamMatching;
    }



}
