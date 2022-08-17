<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\UnitOfWork;

class LaravelUnitOfWork implements UnitOfWork
{

   public function commit() : void {
      DB::commit();
   }

   public function beginTransaction(): void
   {
      DB::beginTransaction();
   }

   public function rollback(): void
   {
      DB::rollBack();
   }
}
