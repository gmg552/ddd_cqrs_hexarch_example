<?php

namespace Qalis\Shared\Application\ChargeMethods\SearchAll;

use Qalis\Shared\Domain\ChargeMethods\ChargeMethodReadRepository;

final class ChargeMethodsSearcher
{
    private ChargeMethodReadRepository $chargeMethodReadRepository;

    public function __construct(ChargeMethodReadRepository $chargeMethodReadRepository)
    {
        $this->chargeMethodReadRepository = $chargeMethodReadRepository;
    }

    public function __invoke(): SearchAllChargeMethodsResponse
    {
        return $this->chargeMethodReadRepository->searchAll();
    }
}
