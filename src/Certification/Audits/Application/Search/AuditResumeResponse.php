<?php

namespace Qalis\Certification\Audits\Application\Search;

class AuditResumeResponse
{
    private string $id;
    private string $operatorName;
    private string $schemeName;
    private ?string $startDate;
    private ?string $endDate;
    private ?string $auditTypeName;
    private ?string $number;

    public function __construct(
        string $id,
        string $operatorName,
        string $schemeName,
        string $startDate = null,
        string $endDate = null,
        string $auditTypeName = null,
        string $number = null
    )
    {
        $this->id = $id;
        $this->operatorName = $operatorName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->schemeName = $schemeName;
        $this->auditTypeName = $auditTypeName;
        $this->number = $number;
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'operatorName' => $this->operatorName,
            'schemeName' => $this->schemeName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'auditTypeName' => $this->auditTypeName,
            'number' => $this->number
        ];
    }
}
