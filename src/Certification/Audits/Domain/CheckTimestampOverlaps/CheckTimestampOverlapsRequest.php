<?php

namespace Qalis\Certification\Audits\Domain\CheckTimestampOverlaps;

use Qalis\Shared\Domain\Bus\Query\Response;

class CheckTimestampOverlapsRequest implements Response
{

    private array $data;
    private string $auditChecklistId;

    public const AUDITED_ITEM_TYPE = 'audited_item';
    public const CHECKLIST_TYPE = 'checklist';

    public function __construct(string $auditChecklistId)
    {
        $this->data = [];
        $this->auditChecklistId = $auditChecklistId;
    }

    public function addTimestamp(string $id, string $auditorId, string $date, string $startTime, string $endTime, string $type, ?string $auditedItemId = null) {
        array_push($this->data, [
            'id' => $id,
            'auditor_id' => $auditorId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => $type,
            'auditedItemId' => $auditedItemId
        ]);
    }

    public function auditChecklistId(): string {
        return $this->auditChecklistId;
    }

    public function toArray(): array {
        return $this->data;
    }

}
