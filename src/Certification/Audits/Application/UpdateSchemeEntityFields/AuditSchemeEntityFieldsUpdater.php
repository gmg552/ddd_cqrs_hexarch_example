<?php

namespace Qalis\Certification\Audits\Application\UpdateSchemeEntityFields;

use function Lambdish\Phunctional\map;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Shared\Application\SchemeEntityFieldValues\Set\SetSchemeEntityFieldValueCommand;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\SchemeEntityFieldValues\Search\SchemeEntityFieldValueSearcher;

class AuditSchemeEntityFieldsUpdater {

    private SchemeEntityFieldValueSearcher $schemeEntityFieldValueSearcher;
    private AuditRepository $auditRepository;

    public function __construct(SchemeEntityFieldValueSearcher $schemeEntityFieldValueSearcher, AuditRepository $auditRepository)
    {
        $this->schemeEntityFieldValueSearcher = $schemeEntityFieldValueSearcher;
        $this->auditRepository = $auditRepository;
    }

    public function __invoke(AuditId $auditId, SetSchemeEntityFieldValueCommand ...$entityFieldValueCommands)
    {
        $audit = $this->auditRepository->find($auditId);
        $fieldValues = $this->schemeEntityFieldValueSearcher->__invoke(...$entityFieldValueCommands);
        $this->auditRepository->update($audit, $fieldValues);
    }

}
