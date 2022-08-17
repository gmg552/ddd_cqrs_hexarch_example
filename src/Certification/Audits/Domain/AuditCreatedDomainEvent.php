<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain;

use Qalis\Shared\Domain\Bus\Event\DomainEvent;

final class AuditCreatedDomainEvent extends DomainEvent
{

    private string $auditSchemeId;

    public function __construct(
        string $id,
        string $auditSchemeId,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
        $this->auditSchemeId = $auditSchemeId;
    }

    public static function eventName(): string
    {
        return 'audit.created';
    }

    public static function fromArray(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self($aggregateId, $body['auditSchemeId'], $eventId, $occurredOn);
    }

    public function toArray(): array
    {
        return [
            'auditSchemeId' => $this->auditSchemeId
        ];
    }

    public function auditSchemeId(): string
    {
        return $this->auditSchemeId;
    }


}
