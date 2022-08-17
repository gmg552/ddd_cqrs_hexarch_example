<?php

namespace Qalis\Shared\Domain\Countries;

use Qalis\Shared\Application\Countries\SearchAll\SearchAllCountriesResponse;

interface CountryReadRepository {
    public function searchAll(): SearchAllCountriesResponse;
    public function getCountryIdByCode(string $countryCode): ?string;
}
