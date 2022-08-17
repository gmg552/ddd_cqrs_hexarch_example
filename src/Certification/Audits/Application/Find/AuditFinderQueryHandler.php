<?php

namespace Qalis\Certification\Audits\Application\Find;

final class AuditFinderQueryHandler {

    private AuditFinder $auditFinder;

    public function __construct(AuditFinder $auditFinder)
    {
        $this->auditFinder = $auditFinder;
    }

    public function __invoke(AuditFinderQuery $query): AuditResponse
    {
        return $this->auditFinder->__invoke($query->id());
    }

}
