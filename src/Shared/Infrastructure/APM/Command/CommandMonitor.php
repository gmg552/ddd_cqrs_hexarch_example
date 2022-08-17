<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Command;


interface CommandMonitor
{
   public function __invoke(string $command, array $params) : void;
}