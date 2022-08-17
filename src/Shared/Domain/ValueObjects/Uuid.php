<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;


use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidUuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

class Uuid implements Stringable
{
   protected $value;
   public function __construct(string $value)
   {
      $this->ensureIsValidUuid($value);
      $this->value = strtolower($value);
   }

   public static function generate(): self
   {
      return new self(Uuid::generateString());
   }

   public static function generateString(): string
   {
      return str_replace("-", "", RamseyUuid::uuid4());
   }

   public static function hash(string $data) : string {
      return substr(hash('ripemd160', $data), 0, 32);
   }

   public function value(): string
   {
      return $this->value;
   }

    public function binValue(): string
    {
        return hex2bin($this->value);
    }

   public function equals(Uuid $other): bool
   {
      return $this->value() === $other->value();
   }

   public function __toString(): string
   {
      return $this->value();
   }

   private function ensureIsValidUuid(string $id): void
   {
      if ((!ctype_xdigit($id)) || (strlen($id)!=32)) {
         throw new InvalidUuid($id, get_called_class());
      }
   }

   public static function isAValidUuid(string $id) : bool{
      if ((!ctype_xdigit($id)) || (strlen($id)!=32)) {
         return false;
      }
      return true;
   }
}
