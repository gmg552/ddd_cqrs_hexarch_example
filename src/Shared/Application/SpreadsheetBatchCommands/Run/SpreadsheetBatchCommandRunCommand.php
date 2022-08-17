<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Run;

use Qalis\Shared\Domain\Bus\Command\Command;

final class SpreadsheetBatchCommandRunCommand extends Command {

    private string $spreadsheetFormatId;
    private string $fileContent;
    private string $originalFileName;
    private array $contextParams;

    public function __construct(
        string $spreadsheetFormatId,
        string $fileContent,
        string $originalFileName,
        array $contextParams
    )
    {
        $this->spreadsheetFormatId = $spreadsheetFormatId;
        $this->fileContent = $fileContent;
        $this->originalFileName = $originalFileName;
        $this->contextParams = $contextParams;
    }

    /**
     * @return string
     */
    public function spreadsheetFormatId(): string
    {
        return $this->spreadsheetFormatId;
    }

    /**
     * @return string
     */
    public function fileContent(): string
    {
        return $this->fileContent;
    }

    /**
     * @return string
     */
    public function originalFileName(): string
    {
        return $this->originalFileName;
    }

    /**
     * @return array
     */
    public function contextParams(): array
    {
        return $this->contextParams;
    }

}
