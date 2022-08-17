<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Event;

use Qalis\Shared\Domain\Bus\Event\DomainEvent;
use Qalis\Shared\Domain\Bus\Event\EventBus;


class InMemoryLaravelEventBus implements EventBus
{
    public function __construct()
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
