<?php

namespace Qalis\Shared\Application\Provinces\SearchByCountry;

final class SearchProvincesQueryHandler
{
    private ProvincesSearcher $provincesSearcher;

    public function __construct(ProvincesSearcher $provincesSearcher)
    {
        $this->provincesSearcher = $provincesSearcher;
    }

    public function __invoke(SearchProvincesQuery $query): SearchProvincesResponse
    {
        return $this->provincesSearcher->__invoke($query->countryId());
    }
}
