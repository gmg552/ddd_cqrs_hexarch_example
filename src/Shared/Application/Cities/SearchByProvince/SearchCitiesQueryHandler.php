<?php

namespace Qalis\Shared\Application\Cities\SearchByProvince;

final class SearchCitiesQueryHandler
{
    private CitiesSearcher $citiesSearcher;

    public function __construct(CitiesSearcher $citiesSearcher)
    {
        $this->citiesSearcher = $citiesSearcher;
    }

    public function __invoke(SearchCitiesQuery $query): SearchCitiesResponse
    {
        return $this->citiesSearcher->__invoke($query->provinceId());
    }
}
