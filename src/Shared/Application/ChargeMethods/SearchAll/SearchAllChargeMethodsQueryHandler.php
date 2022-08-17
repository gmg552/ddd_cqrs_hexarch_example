<?php

namespace Qalis\Shared\Application\ChargeMethods\SearchAll;

final class SearchAllChargeMethodsQueryHandler
{
    private ChargeMethodsSearcher $chargeMethodsSearcher;

    public function __construct(ChargeMethodsSearcher $chargeMethodsSearcher)
    {
        $this->chargeMethodsSearcher = $chargeMethodsSearcher;
    }

    public function __invoke(): SearchAllChargeMethodsResponse
    {
        return $this->chargeMethodsSearcher->__invoke();
    }
}
