<?php

namespace Qalis\Certification\Audits\Application\UpdateNumber;

use Qalis\Certification\AuditCodeRanges\Domain\AuditCodeRangeReadRepository;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditNumber;
use Qalis\Certification\Audits\Domain\GetCode\AuditCodeGetter;
use Qalis\Certification\OperatorCBNumbers\Domain\OperatorCBNumberRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use InvalidArgumentException;
use Qalis\Certification\AuditCodeRanges\Domain\AuditCodeRange;
use Qalis\Certification\Audits\Domain\AuditReadRepository;

class NumberUpdater {

    private AuditRepository $repository;
    private OperatorCBNumberRepository $operatorCBNumberRepository;
    private AuditCodeRangeReadRepository $auditCodeRangeReadRepository;
    private AuditCodeGetter $auditCodeGetter;
    private Audit $audit;
    private AuditReadRepository $auditReadRepository;

    public function __construct(
        AuditRepository $repository,
        OperatorCBNumberRepository $operatorCBNumberRepository,
        AuditCodeRangeReadRepository $auditCodeRangeReadRepository,
        AuditCodeGetter $auditCodeGetter,
        AuditReadRepository $auditReadRepository
    )
    {
        $this->repository = $repository;
        $this->operatorCBNumberRepository = $operatorCBNumberRepository;
        $this->auditCodeRangeReadRepository = $auditCodeRangeReadRepository;
        $this->auditCodeGetter = $auditCodeGetter;
        $this->auditReadRepository = $auditReadRepository;
    }

    public function __invoke(UpdateNumberCommand $command) : void
    {

        //primero nos traemos la auditoría
        $this->audit = $this->repository->find(new AuditId($command->auditId()));

        //hacemos el set
        if ($command->number()) $this->audit->updateNumber(new AuditNumber($command->number()));

        if (!$this->audit->startDate()) {
            throw new InvalidArgumentException("Debe indicar la fecha de auditoría antes establecer la numeración.");
        }

        $auditCodeRangeResponse = $this->auditCodeRangeReadRepository->findByScheme($this->audit->baseSchemeId());
        $this->ensureIsNotDuplicatedIfSetByUser(
            $this->audit->number()->value(),
            $this->audit->operatorId()->value(),
            $this->audit->startDate()->year(),
            $this->audit->baseSchemeId()->value(),
            $this->audit->id()->value(),
            $auditCodeRangeResponse->correlativeTo()
        );
        $operatorCBNumber = $this->operatorCBNumberRepository->findByOperatorAndRange($this->audit->operatorId(), $this->audit->baseSchemeId());
        if (($operatorCBNumber->number()) && ($this->audit->number())) {
            $auditCode = $this->auditCodeGetter->__invoke($operatorCBNumber->number()->value(), $this->audit->number()->value(), $this->audit->startDate(), $auditCodeRangeResponse->format());
            $this->audit->updateCode($auditCode);
        }

        //guardamos
        $this->repository->update($this->audit);
    }

    private function ensureIsNotDuplicatedIfSetByUser(?int $number, string $operatorId, string $year, string $baseSchemeId, string $auditId, string $correlativeTo) : void {
        if($number != null)
        {
            if($correlativeTo == AuditCodeRange::OPERATOR_AND_YEAR)
                $this->ensureIsNotDuplicatedForThisOperatorAndYear($number, $operatorId, $year, $baseSchemeId, $auditId);
        }
    }

    private function ensureIsNotDuplicatedForThisOperatorAndYear(?int $number, string $operatorId, string $year, string $baseSchemeId, string $auditId) : void {
        if($this->auditReadRepository->numberDuplicatedForThisOperatorAndYear($number, $operatorId, $year, $baseSchemeId, $auditId))
            throw new InvalidArgumentException("El número de auditoría <$number> está repetido para este operador, año y esquema.");
    }

}
