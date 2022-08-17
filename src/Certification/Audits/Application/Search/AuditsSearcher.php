<?php

namespace Qalis\Certification\Audits\Application\Search;

use Qalis\Certification\Audits\Domain\AuditReadRepository;
use Qalis\Certification\Shared\Domain\Operators\OperatorId;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;

class AuditsSearcher {

    private AuditReadRepository $repository;

    public function __construct(AuditReadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AuditSearcherQuery $query) : AuditResumesResponse
    {
        return $this->repository->searchByOperatorIdAndSchemeId(new OperatorId($query->operatorId()), new SchemeId($query->baseSchemeId()));
    }

}
