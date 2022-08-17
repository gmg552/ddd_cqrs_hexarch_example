<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Event;

use App\Jobs\AsyncDomainEventJob;
use Qalis\Shared\Domain\Bus\Event\AsyncEventBus;
use Qalis\Shared\Domain\Bus\Event\DomainEvent;

class AsyncInMemoryLaravelEventBus implements AsyncEventBus
{
    public function __construct()
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            AsyncDomainEventJob::dispatch($event);
        }
    }
}
