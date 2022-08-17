<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Bus\Event;

use DateTimeImmutable;
use Qalis\Shared\Domain\Utils as DomainUtils;
use Qalis\Shared\Domain\ValueObjects\Uuid;

abstract class DomainEvent
{
    public string $eventId;
    public string $occurredOn;
    public string $aggregateId;

    public function __construct(string $aggregateId, string $eventId = null, string $occurredOn = null)
    {
        $this->eventId    = $eventId ?: Uuid::generateString();
        $this->occurredOn = $occurredOn ?: DomainUtils::dateToString(new DateTimeImmutable());
        $this->aggregateId = $aggregateId;
    }

    abstract public static function fromArray(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    abstract public function toArray(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
