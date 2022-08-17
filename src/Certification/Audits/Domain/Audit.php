<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Shared\Domain\AuditDecisionMakers\AuditDecisionMakerId;
use Qalis\Certification\Shared\Domain\Auditors\AuditorId;
use Qalis\Certification\Shared\Domain\AuditReviewers\AuditReviewerId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\AuditTypes\AuditTypeId;
use Qalis\Certification\Shared\Domain\ControlLevels\ControlLevelId;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\SchemeProcedures\SchemeProcedureId;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Domain\ValueObjects\PositiveFloatValueObject;

class Audit
{
    private AuditId $id;
    private OperatorId $operatorId;
    private ?AuditorId $realChiefAuditorId;
    private SchemeId $baseSchemeId;
    private ?AuditTypeId $auditTypeId;
    private ?AuditorId $strawChiefAuditorId;
    private ?ControlLevelId $controlLevelId;
    private ?SchemeProcedureId $schemeOrderId;
    private AuditDatePeriod $period;
    private ?PositiveFloatValueObject $workdays;
    private ?AuditNumber $number;
    private ?AuditCode $code;
    private ?AuditNotes $notes;
    private ?DateTimeValueObject $signatureDate;
    private ?AuditSignatureLocation $signatureLocation;
    private ?AuditDriveFolderId $driveFolderId;
    private BoolValueObject $isClosed;
    private ?AuditReviewNotes $reviewNotes;
    private ?AuditDecisionMakerId $auditDecisionMakerId;
    private ?AuditReviewerId $auditReviewerId;

    public const CODE = 'audit';

    public function __construct(
        AuditId $id,
        OperatorId $operatorId,
        SchemeId $baseSchemeId,
        AuditDatePeriod $period,
        BoolValueObject $isClosed,
        ?AuditorId $realChiefAuditorId = null,
        ?AuditTypeId $auditTypeId = null,
        ?AuditorId $strawChiefAuditorId = null,
        ?ControlLevelId $controlLevelId = null,
        ?SchemeProcedureId $schemeOrderId = null,
        ?PositiveFloatValueObject $workdays = null,
        ?AuditNumber $number = null,
        ?AuditCode $code = null,
        ?AuditNotes $notes = null,
        ?DateTimeValueObject $signatureDate = null,
        ?AuditSignatureLocation $signatureLocation = null,
        ?AuditDriveFolderId $driveFolderId = null,
        ?AuditReviewNotes $reviewNotes = null,
        ?AuditDecisionMakerId $auditDecisionMakerId = null,
        ?AuditReviewerId $auditReviewerId = null
    ) {
        $this->id = $id;
        $this->operatorId = $operatorId;
        $this->realChiefAuditorId = $realChiefAuditorId;
        $this->baseSchemeId = $baseSchemeId;
        $this->auditTypeId = $auditTypeId;
        $this->strawChiefAuditorId = $strawChiefAuditorId;
        $this->controlLevelId = $controlLevelId;
        $this->schemeOrderId = $schemeOrderId;
        $this->period = $period;
        $this->workdays = $workdays;
        $this->number = $number;
        $this->code = $code;
        $this->notes = $notes;
        $this->signatureDate = $signatureDate;
        $this->signatureLocation = $signatureLocation;
        $this->driveFolderId = $driveFolderId;
        $this->isClosed = $isClosed;
        $this->reviewNotes = $reviewNotes;
        $this->auditDecisionMakerId = $auditDecisionMakerId;
        $this->auditReviewerId = $auditReviewerId;
    }

    static function create (string $id, string $operatorId, string $baseSchemeId) : Audit {

        return new Audit(
            new AuditId($id),
            new OperatorId($operatorId),
            new SchemeId($baseSchemeId),
            new AuditDatePeriod(null, null),
            new BoolValueObject(false),
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );

    }

    public function toPrimitives(): array{
        return [
            'id' => $this->id,
            'operatorId' => $this->operatorId->value(),
            'realChiefAuditorId' => $this->realChiefAuditorId ? $this->realChiefAuditorId->value() : null,
            'baseSchemeId' => $this->baseSchemeId ? $this->baseSchemeId->value() : null,
            'auditTypeId' => $this->auditTypeId->value(),
            'strawChiefAuditorId' => $this->strawChiefAuditorId->value(),
            'controlLevelId' => $this->controlLevelId->value(),
            'schemeOrderId' => $this->schemeOrderId? $this->schemeOrderId->value() : null,
            'startDate' => $this->period->start(),
            'endDate' => $this->period->end(),
            'workdays' => $this->workdays,
            'number' => $this->number->value(),
            'isClosed' => $this->isClosed->value(),
            'notes' => $this->notes->value(),
            'signatureDate' => $this->signatureDate(),
            'signatureLocation' => $this->signatureLocation(),
            'driveFolderId' => $this->driveFolderId() ? $this->driveFolderId()->value() : null
        ];
    }

    //GETTERS

    public function id(): AuditId {
        return $this->id;
    }

    public function period(): AuditDatePeriod {
        return $this->period;
    }

    public function auditTypeId(): ?AuditTypeId {
        return $this->auditTypeId;
    }

    public function operatorId(): OperatorId {
        return $this->operatorId;
    }

    public function realChiefAuditorId(): ?AuditorId {
        return $this->realChiefAuditorId;
    }

    public function strawChiefAuditorId(): ?AuditorId {
        return $this->strawChiefAuditorId;
    }

    public function controlLevelId(): ?ControlLevelId {
        return $this->controlLevelId;
    }

    public function baseSchemeId(): SchemeId {
        return $this->baseSchemeId;
    }

    public function schemeOrderId(): ?SchemeProcedureId {
        return $this->schemeOrderId;
    }

    public function startDate(): ?DateValueObject {
        return $this->period->start();
    }

    public function endDate(): ?DateValueObject {
        return $this->period->end();
    }

    public function workdays(): ?PositiveFloatValueObject {
        return $this->workdays;
    }

    public function number(): ?AuditNumber {
        return $this->number;
    }

    public function code(): ?AuditCode {
        return $this->code;
    }

    public function notes(): ?AuditNotes {
        return $this->notes;
    }

    public function signatureDate(): ?DateTimeValueObject {
        return $this->signatureDate;
    }

    public function signatureLocation(): ?AuditSignatureLocation {
        return $this->signatureLocation;
    }

    public function driveFolderId(): ?AuditDriveFolderId {
        return $this->driveFolderId;
    }

    /**
     * @return BoolValueObject
     */
    public function isClosed(): BoolValueObject
    {
        return $this->isClosed;
    }

    //SETTERS

    public function updateAuditTypeId(AuditTypeId $auditTypeId): void  {
        $this->auditTypeId = $auditTypeId;
    }

    public function updateRealChiefAuditorId(AuditorId $realChiefAuditorId): void  {
        $this->realChiefAuditorId = $realChiefAuditorId;
    }

    public function updateStrawChiefAuditorId(AuditorId $strawChiefAuditorId): void  {
        $this->strawChiefAuditorId = $strawChiefAuditorId;
    }

    public function updateControlLevelId(ControlLevelId $controlLevelId): void  {
        $this->controlLevelId = $controlLevelId;
    }

    public function updateBaseSchemeId(SchemeId $baseSchemeId): void  {
        $this->baseSchemeId = $baseSchemeId;
    }

    public function updateStartDate(DateValueObject $startDate): void  {
        $this->period->updateStart($startDate);
    }

    public function updateEndDate(DateValueObject $endDate): void  {
        $this->period->updateEnd($endDate);
    }

    public function updateWorkdays(PositiveFloatValueObject $workdays): void  {
        $this->workdays = $workdays;
    }

    public function updateNumber(AuditNumber $number): void {
        $this->number = $number;
    }

    public function updateCode(AuditCode $code): void {
        $this->code = $code;
    }

    public function updateNotes(?AuditNotes $notes): void {
        $this->notes = $notes;
    }

    public function updateDriveFolderId(?AuditDriveFolderId $id): void {
        $this->driveFolderId = $id;
    }

    public function updateSignatureDate(DateTimeValueObject $signatureDate): void {
        $this->signatureDate = $signatureDate;
    }

    public function updateSignatureLocation(?AuditSignatureLocation $signatureLocation): void {
        $this->signatureLocation = $signatureLocation;
    }

    public function updateSchemeOrderId(SchemeProcedureId $schemeOrderId): void {
        $this->schemeOrderId = $schemeOrderId;
    }

    /**
     * @param BoolValueObject $isClosed
     */
    public function updateIsClosed(BoolValueObject $isClosed): void
    {
        $this->isClosed = $isClosed;
    }

    /**
     * @return AuditReviewNotes|null
     */
    public function reviewNotes(): ?AuditReviewNotes
    {
        return $this->reviewNotes;
    }

    /**
     * @param AuditReviewNotes|null $reviewNotes
     */
    public function updateReviewNotes(?AuditReviewNotes $reviewNotes): void
    {
        $this->reviewNotes = $reviewNotes;
    }

    /**
     * @return AuditDecisionMakerId|null
     */
    public function auditDecisionMakerId(): ?AuditDecisionMakerId
    {
        return $this->auditDecisionMakerId;
    }

    /**
     * @param AuditDecisionMakerId|null $auditDecisionMakerId
     */
    public function updateAuditDecisionMakerId(?AuditDecisionMakerId $auditDecisionMakerId): void
    {
        $this->auditDecisionMakerId = $auditDecisionMakerId;
    }

    /**
     * @return AuditReviewerId|null
     */
    public function auditReviewerId(): ?AuditReviewerId
    {
        return $this->auditReviewerId;
    }

    /**
     * @param AuditReviewerId|null $auditReviewerId
     */
    public function updateAuditReviewerId(?AuditReviewerId $auditReviewerId): void
    {
        $this->auditReviewerId = $auditReviewerId;
    }

}

