<?php

namespace Qalis\Certification\Audits\Domain\CheckTimestampOverlaps;

use Qalis\Shared\Domain\Bus\Query\Response;

class CheckTimestampOverlapResponse implements Response
{
    private array $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function addOverlap(string $auditorName, string $date, string $startTime, string $endTime, string $checklistTemplateName, string $auditedItemCode = null) {
        $item = [
            'auditor_name' => $auditorName,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'checklist_template_name' => $checklistTemplateName,
            'audited_item_code' => $auditedItemCode
        ];
        if (!$this->existItemInData($item)) array_push($this->data, $item);
    }

    private function existItemInData(array $item) {
        foreach($this->data as $itemLoop) {
            if (empty(array_diff($item, $itemLoop))) return true;
        }
        return false;
    }

    public function toArray(): array {
        return $this->data;
    }

    public function isEmpty(): bool {
        return count($this->data)!==0 ? false : true;
    }

}
