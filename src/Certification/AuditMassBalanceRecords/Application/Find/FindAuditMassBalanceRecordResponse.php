<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Find;

use function Lambdish\Phunctional\map;
use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Search\SchemeEntityFieldValueResponse;

use Qalis\Shared\Domain\Bus\Query\Response;

class FindAuditMassBalanceRecordResponse implements Response
{
    private string $id;
    private string $productId;
    private array $entityFieldValueResponse;


    public function __construct(
        string $id,
        string $productId,
        array $entityFieldValueResponse
    )
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->entityFieldValueResponse = $entityFieldValueResponse;
    }


    public function toArray() : array {
        return [
            'id' => $this->id,
            'productId' => $this->productId,
            'entityFieldValueResponse' =>  map(static fn(SchemeEntityFieldValueResponse $item) => $item->toArray(), $this->entityFieldValueResponse)
        ];
    }

}
