<?php

namespace Qalis\Certification\Audits\Domain\GetCode;


use Qalis\Certification\Audits\Domain\AuditCode;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

class AuditCodeGetter {

    public function __invoke(int $operatorNumber, int $auditNumber, DateValueObject $auditStartDate, string $codeFormat): AuditCode
    {
        $auditCode = preg_replace('/{\s*operator_number\s*}/i', $operatorNumber, $codeFormat);
        $auditCode = preg_replace('/{\s*audit_number\s*}/i', $auditNumber, $auditCode);
        $year = date("y", strtotime($auditStartDate->__toString()));
        $auditCode = preg_replace('/{\s*audit_yy\s*}/i', $year, $auditCode);
        return new AuditCode($auditCode);
    }

}
