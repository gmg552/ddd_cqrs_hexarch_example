<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\ValueObjects;

use Qalis\Shared\Domain\ValueObjects\Exceptions\InvalidTimePeriod;

class WorkTimePeriod extends TimePeriod
{

   protected float $workdays;

   public function __construct(TimeValueObject $start, TimeValueObject $end)
   {
      parent::__construct($start, $end);
      $this->updateWorkdays();
   }

   public function updateStart(TimeValueObject $newStart) : void {
      parent::updateStart($newStart);
      $this->updateWorkdays();
   }

   public function updateEnd(TimeValueObject $newEnd) : void {
      parent::updateEnd($newEnd);
      $this->updateWorkdays();
   }

   public function workdays() : float{
      return $this->workdays;
   }

   private function updateWorkdays() : void {
      $this->workdays = $this->hours * 0.125;
   }
  
}
