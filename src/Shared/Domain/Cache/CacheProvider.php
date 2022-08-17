<?php

namespace Qalis\Shared\Domain\Cache;



interface CacheProvider  {

   /**
    * Si $key existe en caché devuelve su valor, sino el valor devuelto de ejecutar $callback.
    * El valor se mantiene en cache durante $ttl segundos.
    */
   public function remember(string $key, int $ttl, $callback) ;
   public function put(string $key, $value, $ttl = null): bool;
   public function get(string $key, $default = null);
}
