<?php

namespace Qalis\Shared\Domain\ChargeMethods;

use Qalis\Shared\Application\ChargeMethods\SearchAll\SearchAllChargeMethodsResponse;

interface ChargeMethodReadRepository {
    public function searchAll() : SearchAllChargeMethodsResponse;
}
