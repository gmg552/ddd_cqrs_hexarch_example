<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidDatePeriod;

class DatePeriod
{

   protected ?DateValueObject $start;
   protected ?DateValueObject $end;

   public function __construct(DateValueObject $start = null, DateValueObject $end = null)
   {
      $this->ensureIsAValidPeriod($start, $end);
      $this->start = $start;
      $this->end = $end;
   }

   public function start(): ?DateValueObject
   {
      return $this->start;
   }

   public function end(): ?DateValueObject
   {
      return $this->end;
   }

   public function updateStart(DateValueObject $newStart) : void {
      $this->ensureIsAValidPeriod($newStart, $this->end);
      $this->start = $newStart;
   }

   public function updateEnd(DateValueObject $newEnd) : void {
      $this->ensureIsAValidPeriod($this->start, $newEnd);
      $this->end = $newEnd;
   }

   private function ensureIsAValidPeriod(DateValueObject $start = null, DateValueObject $end = null): void
   {
      if ($start && $end && $start > $end)
         throw new InvalidDatePeriod($start, $end);
   }
}
