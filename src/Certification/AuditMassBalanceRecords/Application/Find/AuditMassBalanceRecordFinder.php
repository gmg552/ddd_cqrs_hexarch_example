<?php

namespace Qalis\Certification\AuditMassBalanceRecords\Application\Find;


use function Lambdish\Phunctional\map;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecord;
use Qalis\Certification\AuditMassBalanceRecords\Domain\AuditMassBalanceRecordReadRepository;
use Qalis\Certification\Shared\Domain\AuditMassBalanceRecords\AuditMassBalanceRecordId;
use Qalis\Certification\Shared\Domain\Entities\EntityCode;
use Qalis\Certification\Shared\Domain\SchemeEntityFields\SchemeEntityFieldReadRepository;
use Qalis\Certification\Shared\Domain\Schemes\SchemeId;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;

class AuditMassBalanceRecordFinder
{

    private AuditMassBalanceRecordReadRepository $auditMassBalanceRecordReadRepository;
    private SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository;

    public function __construct(AuditMassBalanceRecordReadRepository $auditMassBalanceRecordReadRepository, SchemeEntityFieldReadRepository $schemeEntityFieldReadRepository)
    {
        $this->auditMassBalanceRecordReadRepository = $auditMassBalanceRecordReadRepository;
        $this->schemeEntityFieldReadRepository = $schemeEntityFieldReadRepository;
    }


    public function __invoke(AuditMassBalanceRecordId $id): FindAuditMassBalanceRecordResponse
    {
        $schemeEntityFieldQueryParams = $this->auditMassBalanceRecordReadRepository->getEntityFieldQueryParams($id);

        $schemeEntityFields = $this->schemeEntityFieldReadRepository->search(
            new EntityCode(AuditMassBalanceRecord::CODE),
            new DateValueObject($schemeEntityFieldQueryParams->auditStartDate()),
            ...map(static fn(string $stringSchemeId) => new SchemeId($stringSchemeId), $schemeEntityFieldQueryParams->schemeIds()));

        return $this->auditMassBalanceRecordReadRepository->find($id, $schemeEntityFields);

    }


}
