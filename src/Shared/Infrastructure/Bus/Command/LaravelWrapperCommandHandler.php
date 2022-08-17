<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Command;

use Qalis\Shared\Domain\Bus\Command\Command;

/**
 * Envoltorio para poder ejecutar el método por defecto de un handler de Laravel: handle()
 */
final class LaravelWrapperCommandHandler {

   private $handler;
   public function __construct($handler){
      $this->handler = $handler;
   }
   public function handle(Command $command){
      $this->handler->__invoke($command);
   }
}
