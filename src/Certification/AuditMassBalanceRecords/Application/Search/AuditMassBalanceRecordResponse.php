<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Search;

use Qalis\Shared\Domain\Bus\Query\Response;

class AuditMassBalanceRecordResponse implements Response
{
    private string $id;
    private string $productName;

    public function __construct(
        string $id,
        string $productName
    )
    {
        $this->id = $id;
        $this->productName = $productName;
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'productName' => $this->productName
        ];
    }
}
