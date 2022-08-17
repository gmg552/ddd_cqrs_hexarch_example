<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidFullName;

class FullNameValueObject
{
   private StringValueObject $name;
   private ?StringValueObject $surname1;
   private ?StringValueObject $surname2;

   public function __construct(string $name, ?string $surname1, ?string $surname2)
   {
      $this->ensureIsAValidFullName($surname1, $surname2);
      $this->name = new StringValueObject($name);
      $this->surname1 = new StringValueObject($surname1);
      $this->surname2 = new StringValueObject($surname2);
   }

   public function name(): string
   {
      return $this->name->value();
   }

   public function surname1(): ?string{
      return $this->surname1->value();
   }

   public function surname2(): ?string{
      return $this->surname2->value();
   }

   public function __toString()
   {
      return self::toString($this->name(), $this->surname1(), $this->surname2());
   }


   public static function toString(string $name, ?string $surname1, ?string $surname2){
      $fullName = $name;
      if($surname1)
         $fullName.=' '.$surname1;
      if($surname2)
         $fullName.=' '.$surname2;
      return $fullName;
   }

   private function ensureIsAValidFullName(?string $surname1, ?string $surname2) : void {
      if($surname2 != null && $surname1 == null)
         throw new InvalidFullName('El nombre tiene segundo apellido pero no tiene primer apellido');
   }
}
