<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects\Exceptions;

use InvalidArgumentException;

class InvalidFullName extends InvalidArgumentException
{
   public function __construct(string $message)
   {
      parent::__construct($message);
   }
}