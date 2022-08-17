<?php

namespace Qalis\Shared\Domain\Cities;

use Qalis\Shared\Application\Cities\Find\CityResponse;
use Qalis\Shared\Application\Cities\SearchByProvince\SearchCitiesResponse;

interface CityReadRepository {
    public function searchByProvince(string $provinceId): SearchCitiesResponse;
    public function find(string $cityId): CityResponse;
    public function searchByCode(string $cityCode, string $provinceCode, string $countryCode): ?CityResponse;
}
