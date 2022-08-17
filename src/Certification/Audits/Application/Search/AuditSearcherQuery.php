<?php

namespace Qalis\Certification\Audits\Application\Search;

class AuditSearcherQuery {
    private string $operatorId;
    private string $baseSchemeId;

    public function __construct(string $operatorId, string $baseSchemeId) {
        $this->operatorId = $operatorId;
        $this->baseSchemeId = $baseSchemeId;
    }

    public function operatorId(): string {
        return $this->operatorId;
    }

    public function baseSchemeId(): string {
        return $this->baseSchemeId;
    }

}
