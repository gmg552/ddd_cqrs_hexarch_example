<?php

declare(strict_types=1);

namespace Qalis\Certification\Audits\Domain\Exceptions;

use RuntimeException;

final class InvalidAuditNumber extends RuntimeException
{
   public function __construct(string $message)
   {
      parent::__construct($message);
   }

   public static function invalidSize(int $length) : self {
      return new static("El tamaño del número de auditoría no puede ser superior a $length");
   }
}
