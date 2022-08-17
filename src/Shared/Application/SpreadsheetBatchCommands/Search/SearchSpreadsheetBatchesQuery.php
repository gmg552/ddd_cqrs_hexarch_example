<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Search;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchSpreadsheetBatchesQuery extends Query {

    private string $batchCommandCode;

    public function __construct(string $batchCommandCode)
    {
        $this->batchCommandCode = $batchCommandCode;
    }

    /**
     * @return string
     */
    public function batchCommandCode(): string
    {
        return $this->batchCommandCode;
    }

}
