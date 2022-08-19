<?php

namespace Qalis\Certification\Audits\Application\Create;

use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditCreatedDomainEvent;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\OperatorSchemes\Domain\CheckIfAuditableSchemeIsCancelled\AuditableSchemeIsCancelledChecker;
use Qalis\Shared\Domain\Bus\Event\EventBus;
use Qalis\Shared\Domain\UnitOfWork;
use Exception;

class AuditCreator {

    private AuditRepository $repository;
    private AuditableSchemeIsCancelledChecker $auditableSchemeIsCancelledChecker;
    private UnitOfWork $unitOfWork;
    private EventBus $eventBus;

    public function __construct(AuditRepository $repository, AuditableSchemeIsCancelledChecker $auditableSchemeIsCancelledChecker, UnitOfWork $unitOfWork, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->auditableSchemeIsCancelledChecker = $auditableSchemeIsCancelledChecker;
        $this->unitOfWork = $unitOfWork;
        $this->eventBus = $eventBus;
    }

    public function __invoke(
        string $id,
        string $operatorId,
        string $auditSchemeId
    ) : void
    {
        $this->ensureSchemeIsNotCancelled($operatorId, $auditSchemeId);

        $audit = Audit::createFromPrimitives(
            $id,
            $operatorId,
            $auditSchemeId
        );

        try {

            $this->unitOfWork->beginTransaction();

            $this->repository->create($audit);
            $this->eventBus->publish(new AuditCreatedDomainEvent($audit->id()->value(), $audit->baseSchemeId()->value()));

            $this->unitOfWork->commit();

        } catch(Exception $exception) {
            $this->unitOfWork->rollback();
            throw $exception;
        }

    }

    private function ensureSchemeIsNotCancelled(string $operatorId, string $auditSchemeId) {
        if ($this->auditableSchemeIsCancelledChecker->__invoke($operatorId, $auditSchemeId)) {
            throw new \RuntimeException("No se puede crear una auditoría si el operador ha dado de baja el esquema");
        }
    }



}
