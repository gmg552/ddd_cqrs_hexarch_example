<?php

namespace Qalis\Shared\Application\Countries\SearchAll;

use Qalis\Shared\Domain\Countries\CountryReadRepository;

final class AllCountriesSearcher
{
    private CountryReadRepository $countryReadRepository;

    public function __construct(CountryReadRepository $countryReadRepository)
    {
        $this->countryReadRepository = $countryReadRepository;
    }

    public function __invoke(): SearchAllCountriesResponse
    {
        return $this->countryReadRepository->searchAll();
    }
}
