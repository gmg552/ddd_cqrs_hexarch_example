<?php
declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Audits\Domain\Exceptions\InvalidAuditNumber;
use Qalis\Shared\Domain\ValueObjects\PositiveIntValueObject;

class AuditNumber extends PositiveIntValueObject
{
    public function __construct(int $value)
    {
        $this->ensureSizeIsLowerThanLimit($value);
        parent::__construct($value);
    }

    private function ensureSizeIsLowerThanLimit(int $value): void
    {
        if ($value > parent::MAX_SIZE_INT)
            throw InvalidAuditNumber::invalidSize(parent::MAX_SIZE_INT);
    }
}
