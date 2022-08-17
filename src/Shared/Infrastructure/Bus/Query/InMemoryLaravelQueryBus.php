<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Bus\Query;

use Illuminate\Support\Facades\Bus;
use Qalis\Shared\Domain\Bus\Query\Query;
use Qalis\Shared\Domain\Bus\Query\QueryBus;
use Qalis\Shared\Domain\Bus\Query\Response;
use Qalis\Shared\Infrastructure\APM\Query\QueryMonitor;

final class InMemoryLaravelQueryBus implements QueryBus
{

    private QueryMonitor $queryMonitor;

    public function __construct(QueryMonitor $queryMonitor)
    {
        $this->queryMonitor = $queryMonitor;
    }

   /**
    * Envia un comando por el command bus. El command handler por defecto se obtiene a partir del nombre del comando + la palabra Handler
    *
    */
   public function ask(Query $query): Response
   {
      $handler = app()->make(get_class($query).'Handler');
      $response = Bus::dispatchNow($query, new LaravelWrapperQueryHandler($handler));
      $this->queryMonitor->__invoke(get_class($query), $query->toArray());
      return $response;
   }
}


