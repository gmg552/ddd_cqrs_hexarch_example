<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\APM\Log;



interface Logger
{
   const INFO_LEVEL = 'info';
   const DEBUG_LEVEL = 'debug';
   //A SER COmPLETEADO

   const LOG_START = 'log_start';
   const LOG_END = 'log_end';

   public function __invoke(string $level, string $message, array $tags = null, array $bindings = null) : void;
}
