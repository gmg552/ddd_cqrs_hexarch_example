<?php

namespace Qalis\Certification\Shared\Domain\SchemeEntityFields\Search;

class SchemeEntityFieldQueryParamsResponse
{

    private string $auditStartDate;
    private array $schemeIds;

    public function __construct(
        string $auditStartDate,
        array $schemeIds
    )
    {
        $this->auditStartDate = $auditStartDate;
        $this->schemeIds = $schemeIds;
    }

    public function auditStartDate(): string {
        return $this->auditStartDate;
    }

    public function schemeIds(): array {
        return $this->schemeIds;
    }

}
