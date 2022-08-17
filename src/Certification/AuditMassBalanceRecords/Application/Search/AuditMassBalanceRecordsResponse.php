<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Search;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class AuditMassBalanceRecordsResponse extends CollectionResponse
{
    public function __construct(AuditMassBalanceRecordResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return AuditMassBalanceRecordResponse::class;
    }
}
