<?php

namespace Qalis\Shared\Application\SpreadhseetBatchCommandAttempts\DownloadFile;

final class SpreadsheetBatchCommandAttemptResponse
{

    private string $id;
    private string $fileName;
    private string $commandCode;

    public function __construct(
        string $id,
        string $fileName,
        string $commandCode
    )
    {
        $this->id = $id;
        $this->fileName = $fileName;
        $this->commandCode = $commandCode;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function fileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function commandCode(): string
    {
        return $this->commandCode;
    }



}
