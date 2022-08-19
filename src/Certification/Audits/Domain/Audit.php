<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\AuditTypes\AuditTypeId;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

/**
 * An audit for a certification body
 * 
 * @author Guillermo Martinez García <gmg552@gmail.com>
 * @access public
 */
class Audit
{
    private AuditId $id;
    private OperatorId $operatorId;
    private SchemeId $baseSchemeId;
    private ?AuditTypeId $auditTypeId;
    private AuditDatePeriod $period;
    private ?AuditNumber $number;
    private ?AuditCode $code;

    /**
     * Entity code
     */
    public const CODE = 'audit';

    /**
     * Constructor
     * 
     * @access public
     * @param AuditId $id Id
     * @param OperatorId $operatorId Operator id
     * @param SchemeId $baseSchemeId Base scheme id
     * @param AuditDatePeriod $period Start and end date
     * @param AuditTypeId|null $auditTypeId Audit type
     * @param AuditNumber|null $number Number
     * @param AuditCode|null $code Code
     */
    public function __construct(
        AuditId $id,
        OperatorId $operatorId,
        SchemeId $baseSchemeId,
        AuditDatePeriod $period,
        ?AuditTypeId $auditTypeId = null,
        ?AuditNumber $number = null,
        ?AuditCode $code = null
    ) {
        $this->id = $id;
        $this->operatorId = $operatorId;
        $this->baseSchemeId = $baseSchemeId;
        $this->auditTypeId = $auditTypeId;
        $this->period = $period;
        $this->number = $number;
        $this->code = $code;
    }

    /**
     * Create a new Audit from primitive paramaters
     *
     * @param string $id Id
     * @param string $operatorId Operator Id
     * @param string $baseSchemeId Related base scheme Id 
     * @return Audit
     */
    static function createFromPrimitives(string $id, string $operatorId, string $baseSchemeId) : Audit {

        return new Audit(
            new AuditId($id),
            new OperatorId($operatorId),
            new SchemeId($baseSchemeId),
            new AuditDatePeriod(null, null)
        );

    }

    /**
     * Return this audit as an array of primitives
     *
     * @return array
     */
    public function toPrimitives(): array{
        return [
            'id' => $this->id,
            'operatorId' => $this->operatorId->value(),
            'baseSchemeId' => $this->baseSchemeId->value(),
            'auditTypeId' => $this->auditTypeId? $this->auditTypeId : null,
            'startDate' => $this->period? $this->period->start() : null,
            'endDate' => $this->period? $this->period->end() : null,
            'number' => $this->number? $this->number->value() : null,
            'code' => $this->code? $this->code->value() : null,
        ];
    }

    //GETTERS
    /**
     * Get id
     *
     * @return AuditId
     */
    public function id(): AuditId {
        return $this->id;
    }


    /**
     * Get audit date period
     *
     * @return AuditDatePeriod
     */
    public function period(): AuditDatePeriod {
        return $this->period;
    }

    /**
     * Get audit type
     *
     * @return AuditTypeId|null
     */
    public function auditTypeId(): ?AuditTypeId {
        return $this->auditTypeId;
    }

    /**
     * Get operator
     *
     * @return OperatorId
     */
    public function operatorId(): OperatorId {
        return $this->operatorId;
    }

    /**
     * Get base scheme
     *
     * @return SchemeId
     */
    public function baseSchemeId(): SchemeId {
        return $this->baseSchemeId;
    }

    /**
     * Get start date
     *
     * @return DateValueObject|null
     */
    public function startDate(): ?DateValueObject {
        return $this->period->start();
    }

    /**
     * Get end date
     *
     * @return DateValueObject|null
     */
    public function endDate(): ?DateValueObject {
        return $this->period->end();
    }

    /**
     * Get number
     *
     * @return AuditNumber|null
     */
    public function number(): ?AuditNumber {
        return $this->number;
    }

    public function code(): ?AuditCode {
        return $this->code;
    }

    //SETTERS

    /**
     * Update audit type
     *
     * @param AuditTypeId $auditTypeId
     * @return void
     */
    public function updateAuditTypeId(AuditTypeId $auditTypeId): void  {
        $this->auditTypeId = $auditTypeId;
    }

    /**
     * Update start date
     *
     * @param DateValueObject $startDate
     * @return void
     */
    public function updateStartDate(DateValueObject $startDate): void  {
        $this->period->updateStart($startDate);
    }

    /**
     * Update end date
     *
     * @param DateValueObject $endDate
     * @return void
     */
    public function updateEndDate(DateValueObject $endDate): void  {
        $this->period->updateEnd($endDate);
    }

    /**
     * Update number
     *
     * @param AuditNumber $number
     * @return void
     */
    public function updateNumber(AuditNumber $number): void {
        $this->number = $number;
    }

    /**
     * Update code
     *
     * @param AuditCode $code
     * @return void
     */
    public function updateCode(AuditCode $code): void {
        $this->code = $code;
    }

}

