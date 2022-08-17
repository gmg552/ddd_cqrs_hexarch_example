<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Bus\Event;

interface AsyncEventBus
{
    public function publish(DomainEvent ...$events): void;
}
