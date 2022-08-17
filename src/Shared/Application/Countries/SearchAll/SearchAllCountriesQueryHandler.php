<?php

namespace Qalis\Shared\Application\Countries\SearchAll;

final class SearchAllCountriesQueryHandler
{
    private AllCountriesSearcher $allCountriesSearcher;

    public function __construct(AllCountriesSearcher $allCountriesSearcher)
    {
        $this->allCountriesSearcher = $allCountriesSearcher;
    }

    public function __invoke(): SearchAllCountriesResponse
    {
        return $this->allCountriesSearcher->__invoke();
    }
}
