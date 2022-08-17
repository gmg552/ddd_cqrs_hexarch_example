<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Query;


use Qalis\Shared\Domain\Bus\Query\Query;

/**
 * Envoltorio para poder ejecutar el método por defecto de un handler de Laravel: handle()
 */
final class LaravelWrapperQueryHandler {

   private $handler;
   public function __construct($handler){
      $this->handler = $handler;
   }
   public function handle(Query $query){
      return $this->handler->__invoke($query);
   }
}
