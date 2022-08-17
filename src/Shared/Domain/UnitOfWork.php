<?php

namespace Qalis\Shared\Domain;

interface UnitOfWork
{
   public function commit(): void;
   public function rollback(): void;
   public function beginTransaction() : void;
}
