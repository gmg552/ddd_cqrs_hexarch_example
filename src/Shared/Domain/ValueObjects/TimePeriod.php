<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;


use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidTimePeriod;

class TimePeriod
{

   protected TimeValueObject $start;
   protected TimeValueObject $end;
   protected float $hours;

   public function __construct(TimeValueObject $start, TimeValueObject $end)
   {
      $this->ensureIsACompletePeriod($start, $end);
      $this->ensureIsAConsistentPeriod($start, $end);
      $this->start = $start;
      $this->end = $end;
      $this->updateHours();
   }

   public function start(): TimeValueObject
   {
      return $this->start;
   }

   public function end(): TimeValueObject
   {
      return $this->end;
   }

   public function hours() : float{
      return $this->hours;
   }

   private function updateHours() : void{
      $interval = $this->start->diff($this->end);
      $this->hours = ($interval->days * 24) + $interval->h;
   }

   public function updateStart(TimeValueObject $newStart) : void {
      $this->ensureIsAConsistentPeriod($newStart, $this->end);
      $this->start = $newStart;
      $this->updateHours();
   }

   public function updateEnd(TimeValueObject $newEnd) : void {
      $this->ensureIsAConsistentPeriod($this->start, $newEnd);
      $this->ensureIsACompletePeriod($this->start, $newEnd);
      $this->end = $newEnd;
      $this->updateHours();
   }

   private function ensureIsAConsistentPeriod(TimeValueObject $start, TimeValueObject $end): void
   {
      if ($start && $end && $start > $end)
         throw InvalidTimePeriod::incosistent($start, $end);
   }

   private function ensureIsACompletePeriod(TimeValueObject $start, TimeValueObject $end): void
   {
      if (null == $start && null != $end)
         throw InvalidTimePeriod::incomplete();
   }
}
