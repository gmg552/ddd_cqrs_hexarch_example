<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Query;


interface QueryMonitor
{
   public function __invoke(string $query, array $params) : void;
}