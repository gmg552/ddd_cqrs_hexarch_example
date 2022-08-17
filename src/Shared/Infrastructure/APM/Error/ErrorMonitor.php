<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Error;


interface ErrorMonitor
{
   public function __invoke(string $type, array $trace) : void;
}