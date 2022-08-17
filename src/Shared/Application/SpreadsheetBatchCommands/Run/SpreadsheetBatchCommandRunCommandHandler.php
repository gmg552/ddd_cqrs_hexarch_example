<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Run;


use Qalis\Shared\Utils\FileUtils;

class SpreadsheetBatchCommandRunCommandHandler
{
    private SpreadsheetBatchCommandRunner $spreadsheetBatchCommandRunner;

    public function __construct(SpreadsheetBatchCommandRunner $spreadsheetBatchCommandRunner)
    {
        $this->spreadsheetBatchCommandRunner = $spreadsheetBatchCommandRunner;
    }

    public function __invoke(SpreadsheetBatchCommandRunCommand $command): void
    {
        $this->spreadsheetBatchCommandRunner->__invoke($command->spreadsheetFormatId(), $command->fileContent(), $command->originalFileName(), $command->contextParams());
    }

}
