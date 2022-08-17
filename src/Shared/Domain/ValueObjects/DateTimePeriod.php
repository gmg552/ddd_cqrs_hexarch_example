<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;


use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidDateTimePeriod;

class DateTimePeriod
{

   protected ?DateTimeValueObject $start;
   protected ?DateTimeValueObject $end;

   public function __construct(DateTimeValueObject $start = null, DateTimeValueObject $end = null)
   {
      $this->ensureIsAValidPeriod($start, $end);
      $this->start = $start;
      $this->end = $end;
   }

   public function start(): ?DateTimeValueObject
   {
      return $this->start;
   }

   public function end(): ?DateTimeValueObject
   {
      return $this->end;
   }

   public function updateStart(DateTimeValueObject $newStart) : void {
      $this->ensureIsAValidPeriod($newStart, $this->end);
      $this->start = $newStart;
   }

   public function updateEnd(DateTimeValueObject $newEnd) : void {
      $this->ensureIsAValidPeriod($this->start, $newEnd);
      $this->end = $newEnd;
   }

   private function ensureIsAValidPeriod(DateTimeValueObject $start = null, DateTimeValueObject $end = null): void
   {
      if ($start && $end && $start > $end)
         throw new InvalidDateTimePeriod($start, $end);
   }
}
