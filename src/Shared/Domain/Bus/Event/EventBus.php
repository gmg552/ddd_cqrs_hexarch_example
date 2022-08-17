<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Bus\Event;

use Qalis\Shared\Domain\Bus\Event\DomainEvent;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
