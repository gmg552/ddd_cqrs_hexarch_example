<?php

namespace Qalis\Certification\Audits\Domain\SearchTimestampsForValidation;

use DateTime;
use Qalis\Shared\Domain\Bus\Query\Response;

class SearchTimestampsForValidationResponse implements Response
{

    private string $id;
    private string $auditorFullName;
    private string $auditorId;
    private DateTime $date;
    private string $startTime;
    private string $endTime;
    private string $checklistTemplateName;
    private string $type;
    private ?string $auditedItemCode;

    public const AUDITED_ITEM_TYPE = 'audited_item';
    public const CHECKLIST_TYPE = 'checklist';

    public function __construct(
        string $id,
        string $auditorFullName,
        string $auditorId,
        DateTime $date,
        string $startTime,
        string $endTime,
        string $checklistTemplateName,
        string $type,
        ?string $auditedItemCode = null
    )
    {
        $this->id = $id;
        $this->auditorFullName = $auditorFullName;
        $this->auditorId = $auditorId;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->checklistTemplateName = $checklistTemplateName;
        $this->type = $type;
        $this->auditedItemCode = $auditedItemCode;
    }

    public function id(): string {
        return $this->id;
    }

    public function auditorFullName(): string {
        return $this->auditorFullName;
    }

    public function checklistTemplateName(): string {
        return $this->checklistTemplateName;
    }

    public function type(): string {
        return $this->type;
    }

    public function auditedItemCode(): ?string {
        return $this->auditedItemCode;
    }

    public function auditorId(): string {
        return $this->auditorId;
    }

    public function date(): DateTime {
        return $this->date;
    }

    public function startTime(): string {
        return $this->startTime;
    }

    public function endTime(): string {
        return $this->endTime;
    }

}
