<?php

namespace Qalis\Certification\Audits\Application\Find;

use Qalis\Shared\Domain\Bus\Query\Response;
use function Lambdish\Phunctional\map;
use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Search\SchemeEntityFieldValueResponse;

class AuditResponse implements Response
{

    private string $id;
    private string $operatorId;
    private string $operatorFullName;
    private string $baseSchemeId;
    private string $baseSchemeName;
    private ?string $operatorSchemeCode;
    private ?string $operatorNumber;
    private ?string $realChiefAuditorId;
    private ?string $auditTypeId;
    private ?string $strawChiefAuditorId;
    private ?string $controlLevelId;
    private ?string $startDate;
    private ?string $endDate;
    private ?float $workdays;
    private ?int $number;
    private ?string $notes;
    private ?string $signatureDate;
    private ?string $signatureLocation;
    private ?string $schemeOrderDate;
    private array $entityFieldValueResponse;
    private ?string $driveFolderId;
    private ?string $reviewNotes;
    private ?string $reviewerId;
    private ?string $decisionMakerId;

    public function __construct(
        string $id,
        string $operatorId,
        string $operatorFullName,
        string $baseSchemeId,
        string $baseSchemeName,
        string $operatorSchemeCode = null,
        string $operatorNumber = null,
        string $realChiefAuditorId = null,
        string $auditTypeId = null,
        string $strawChiefAuditorId = null,
        string $controlLevelId = null,
        string $startDate = null,
        string $endDate = null,
        string $workdays = null,
        string $number = null,
        string $notes = null,
        string $signatureDate = null,
        string $signatureLocation = null,
        string $schemeOrderDate = null,
        array $entityFieldValueResponse = null,
        string $driveFolderId = null,
        string $reviewNotes = null,
        string $reviewerId = null,
        string $decisionMakerId = null
    )
    {
        $this->id = $id;
        $this->operatorId = $operatorId;
        $this->operatorFullName = $operatorFullName;
        $this->baseSchemeId = $baseSchemeId;
        $this->baseSchemeName = $baseSchemeName;
        $this->operatorSchemeCode = $operatorSchemeCode;
        $this->operatorNumber = $operatorNumber;
        $this->realChiefAuditorId = $realChiefAuditorId;
        $this->auditTypeId = $auditTypeId;
        $this->strawChiefAuditorId = $strawChiefAuditorId;
        $this->controlLevelId = $controlLevelId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->workdays = $workdays;
        $this->number = $number;
        $this->notes = $notes;
        $this->signatureDate = $signatureDate;
        $this->signatureLocation = $signatureLocation;
        $this->schemeOrderDate = $schemeOrderDate;
        $this->entityFieldValueResponse = $entityFieldValueResponse;
        $this->driveFolderId = $driveFolderId;
        $this->reviewNotes = $reviewNotes;
        $this->reviewerId = $reviewerId;
        $this->decisionMakerId = $decisionMakerId;
    }

    public function id(): string {
        return $this->id;
    }

    public function startDate(): ?string {
        return $this->startDate;
    }


    public function driveFolderId(): ?string {
        return $this->driveFolderId;
    }

    public function baseSchemeId(): string {
        return $this->baseSchemeId;
    }

    public function operatorId(): string {
        return $this->operatorId;
    }

    public function toArray() : array {
        return [
            'id' => $this->id,
            'operatorId' => $this->operatorId,
            'operatorFullName' => $this->operatorFullName,
            'realChiefAuditorId' => $this->realChiefAuditorId,
            'baseSchemeId' => $this->baseSchemeId,
            'baseSchemeName' => $this->baseSchemeName,
            'operatorSchemeCode' => $this->operatorSchemeCode,
            'operatorNumber' => $this->operatorNumber,
            'auditTypeId' => $this->auditTypeId,
            'strawChiefAuditorId' => $this->strawChiefAuditorId,
            'controlLevelId' => $this->controlLevelId,
            'startDate' => $this->startDate? $this->startDate : null,
            'endDate' => $this->endDate? $this->endDate: null,
            'workdays' => $this->workdays,
            'number' => $this->number,
            'notes' => $this->notes,
            'signatureDate' => $this->signatureDate? $this->signatureDate: null,
            'signatureLocation' => $this->signatureLocation? $this->signatureLocation: null,
            'schemeOrderDate' => $this->schemeOrderDate?? null,
            'driveFolderId' => $this->driveFolderId?? null,
            'reviewNotes' => $this->reviewNotes,
            'decisionMakerId' => $this->decisionMakerId,
            'reviewerId' => $this->reviewerId,
            'entityFieldValueResponse' =>  map(static fn(SchemeEntityFieldValueResponse $item) => $item->toArray(), $this->entityFieldValueResponse)
        ];
    }
}
