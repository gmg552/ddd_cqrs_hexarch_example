<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Persistence;


interface PersistenceMonitor
{
   public function __invoke(string $statement, array $bindings, float $time) : void;
}