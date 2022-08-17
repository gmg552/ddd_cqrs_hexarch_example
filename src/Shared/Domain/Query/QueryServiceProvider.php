<?php

namespace Qalis\Shared\Domain\Query;


/**
 * Representa un servicio de consultas sobre entidades del dominio
 */
interface QueryServiceProvider {
   public function __invoke(Query $query) : QueryResult;
}
