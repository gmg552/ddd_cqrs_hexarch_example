<?php

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Audits\Application\Search\AuditResumesResponse;
use Qalis\Certification\Audits\Application\Find\AuditResponse;
use Qalis\Certification\Audits\Domain\CheckTimestampOverlaps\CheckTimestampOverlapsRequest;
use Qalis\Certification\Audits\Domain\LoadAPIAuditDetail\GlobalgapAuditReportResponse;
use Qalis\Certification\Shared\Domain\AuditChecklists\AuditChecklistId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

interface AuditReadRepository {
    public function searchByOperatorIdAndSchemeId(OperatorId $operatorId, SchemeId $baseSchemeId): AuditResumesResponse;
    public function find(AuditId $auditId, SchemeEntityFieldsResponse $fields): AuditResponse;
    public function checkIfHasAChecklistWithThisVersion(string $auditId, string $versionId): bool;
    public function getRealAuditorIdByChecklist(AuditChecklistId $auditChecklistId) : ?string;
//    public function getAuditDetailsForGGApi(string $auditId) : GlobalgapAuditReportResponse;
    public function numberDuplicatedForThisOperatorAndYear(int $number, string $operatorId, string $year, string $baseSchemeId, string $auditId) : bool;
//    public function getQualityManagement(string $auditId): GlobalgapAuditReportResponse;
    public function searchTimestampsForValidationByAudit(string $auditId): array;
    public function completeTimestampsForValidation(CheckTimestampOverlapsRequest $request): array;
    public function getEarlierAuditDateByIteration(string $iterationId): ?DateValueObject;
    public function getNextAuditDateByIteration(string $iterationId): ?DateValueObject;
    public function lastAuditEndDateBySchemeOrderBaseScheme(string $schemeOrderBaseSchemeId, string $operatorId): ?DateValueObject;
    public function auditDateBeforeSchemeProcedure(string $schemeProcedureId): ?DateValueObject;
    public function auditDateAfterSchemeProcedure(string $schemeProcedureId): ?DateValueObject;
    public function lastAuditEndDate(string $operatorId, string $schemeId): ?DateValueObject;
    public function isClosed(string $auditId): bool;
    public function checkHasCertificateIssued(string $auditId): bool;
}
