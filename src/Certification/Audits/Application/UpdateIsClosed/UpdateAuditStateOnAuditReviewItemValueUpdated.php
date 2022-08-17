<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Application\UpdateIsClosed;

use Qalis\Certification\AuditReviewItemValues\Domain\AuditReviewItemValueUpdatedDomainEvent;
use Qalis\Certification\Audits\Domain\AuditReadRepository;
use Qalis\Certification\Audits\Domain\AuditRepository;
use Qalis\Certification\Shared\Domain\Audits\AuditId;
use Qalis\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;

final class UpdateAuditStateOnAuditReviewItemValueUpdated implements DomainEventSubscriber
{
    private AuditRepository $auditRepository;
    private AuditReadRepository $auditReadRepository;

    public function __construct(
        AuditRepository $auditRepository,
        AuditReadRepository $auditReadRepository
    )
    {
        $this->auditRepository = $auditRepository;
        $this->auditReadRepository = $auditReadRepository;
    }

    public static function subscribedTo(): array
    {
        return [AuditReviewItemValueUpdatedDomainEvent::class];
    }

    public function __invoke(AuditReviewItemValueUpdatedDomainEvent $event): void
    {
        $audit = $this->auditRepository->find(new AuditId($event->auditId()));
        $isClosed = $this->auditReadRepository->isClosed($event->auditId());
        $audit->updateIsClosed(new BoolValueObject($isClosed));
        $this->auditRepository->update($audit);
    }
}
