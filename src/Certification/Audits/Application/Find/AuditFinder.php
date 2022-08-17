<?php

namespace Qalis\Certification\Audits\Application\Find;

use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditReadRepository;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Certification\Shared\Domain\Entities\EntityCode;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\Search\SchemeEntityFieldsResponse;

class AuditFinder {

    private AuditReadRepository $repository;
    private AuditRepository $auditRepository;
    private SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository;

    public function __construct(AuditReadRepository $repository, AuditRepository $auditRepository, SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository)
    {
        $this->repository = $repository;
        $this->auditRepository = $auditRepository;
        $this->schemeEntityFieldReadRepository = $schemeEntityFieldReadRepository;
    }

    public function __invoke(string $auditId) : AuditResponse
    {
        $audit = $this->auditRepository->find2(new AuditId($auditId));
        $schemeEntityFields = ($audit->startDate()) ? $this->schemeEntityFieldReadRepository->search(new EntityCode(Audit::CODE), $audit->startDate(), $audit->baseSchemeId()) : new SchemeEntityFieldsResponse();
        return $this->repository->find(new AuditId($auditId), $schemeEntityFields);
    }

}
