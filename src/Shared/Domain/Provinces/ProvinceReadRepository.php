<?php

namespace Qalis\Shared\Domain\Provinces;

use Qalis\Shared\Application\Provinces\Find\FindProvinceResponse;
use Qalis\Shared\Application\Provinces\SearchByCountry\SearchProvincesResponse;

interface ProvinceReadRepository {
    public function searchByCountry(string $countryId): SearchProvincesResponse;
    public function find(string $provinceId): FindProvinceResponse;
}
