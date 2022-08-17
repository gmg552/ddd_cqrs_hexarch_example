<?php

namespace Qalis\Certification\Audits\Application\Search;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class AuditResumesResponse extends CollectionResponse
{

    public function __construct(AuditResumeResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return AuditResumeResponse::class;
    }


}
