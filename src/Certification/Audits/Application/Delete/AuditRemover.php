<?php

namespace Qalis\Certification\Audits\Application\Delete;

use Qalis\Certification\AuditChecklists\Domain\AuditChecklistRepository;
use Qalis\Certification\Auditors\Domain\AuditorReadRepository;
use Qalis\Certification\Auditors\Infrastructure\Legacy\Persistence\AuditorRepository;
use Qalis\Certification\Audits\Domain\Audit;
use Qalis\Certification\Audits\Domain\AuditReadRepository;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\Bus\Event\EventBus;
use Qalis\Shared\Domain\UnitOfWork;
use RuntimeException;

class AuditRemover {

    private AuditRepository $auditRepository;
    private AuditReadRepository $auditReadRepository;
    private AuditChecklistRepository $auditChecklistRepository;
    private EventBus $bus;
    private UnitOfWork $unitOfWork;
    private AuditorReadRepository $auditorReadRepository;

    public function __construct(
        AuditRepository $auditRepository,
        AuditReadRepository $auditReadRepository,
        AuditChecklistRepository $auditChecklistRepository,
        EventBus $bus,
        UnitOfWork $unitOfWork,
        AuditorReadRepository $auditorReadRepository
    )
    {
        $this->auditRepository = $auditRepository;
        $this->auditChecklistRepository = $auditChecklistRepository;
        $this->bus = $bus;
        $this->unitOfWork = $unitOfWork;
        $this->auditReadRepository = $auditReadRepository;
        $this->auditorReadRepository = $auditorReadRepository;
    }

    public function __invoke(string $id) : void
    {
        $audit = $this->auditRepository->find(new AuditId($id));
        $this->ensureAuthenticatedUserIsChiefAuditor($audit);
        $this->ensureNoCertificateIsIssued($audit->id());

        $this->unitOfWork->beginTransaction();

        $this->auditChecklistRepository->deleteAuditChecklistsFromAudit(new AuditId($id));
        $this->auditRepository->delete(new AuditId($id));

        $this->unitOfWork->commit();
    }

    private function ensureAuthenticatedUserIsChiefAuditor(Audit $audit) {
        $auditorId = $this->auditorReadRepository->getAuthenticatedAuditorId();
        if (($auditorId) && ($audit->realChiefAuditorId()->value() !== $auditorId))
            throw new RuntimeException("Solo el auditor jefe puede eliminar esta auditoría.");
    }

    private function ensureNoCertificateIsIssued(string $auditId) {

        if ($this->auditReadRepository->checkHasCertificateIssued($auditId)) {
            throw new RuntimeException("No se puede eliminar una auditoría con certificado emitido.");
        }

    }

}
