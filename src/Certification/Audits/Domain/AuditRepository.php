<?php

namespace Qalis\Certification\Audits\Domain;

use Qalis\Certification\Shared\Domain\AuditedSchemes\AuditedSchemeId;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\SchemeEntityFieldValues;
use Qalis\Shared\Domain\Criteria\Criteria;

interface AuditRepository {
    public function create(Audit $audit): void;
    public function update(Audit $audit, SchemeEntityFieldValues $fieldValues = null): void;
    public function find(AuditId $auditId): Audit;
    public function find2(AuditId $auditId): Audit;
    //public function searchByCriteria(Criteria $criteria) : array;
    public function findByAuditedScheme(AuditedSchemeId $auditedSchemeId): Audit;
    public function delete(AuditId $id): void;
}
