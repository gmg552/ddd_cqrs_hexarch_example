<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Command;

use Qalis\Shared\Domain\Bus\Command\Command;
use Qalis\Shared\Domain\Bus\Command\CommandBus;
use Illuminate\Support\Facades\Bus;
use Qalis\Shared\Infrastructure\APM\Command\CommandMonitor;

final class InMemoryLaravelCommandBus implements CommandBus
{

    private CommandMonitor $commandMonitor;

   public function __construct(CommandMonitor $commandMonitor)
   {
       $this->commandMonitor = $commandMonitor;
   }

   /**
    * Envia un comando por el command bus. El command handler por defecto se obtiene a partir del nombre del comando + la palabra Handler
    */
   public function dispatch(Command $command): void
   {
      $handler = app()->make(get_class($command).'Handler');
      Bus::dispatchNow($command, new LaravelWrapperCommandHandler($handler));
      $this->commandMonitor->__invoke(get_class($command), $command->toArray());
   }
}


