<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Cities;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Cities\Find\CityResponse;
use Qalis\Shared\Application\Cities\SearchByProvince\SearchCitiesResponse;
use Qalis\Shared\Application\Cities\SearchByProvince\SearchCityResponse;
use Qalis\Shared\Application\Countries\SearchAll\SearchAllCountriesResponse;
use Qalis\Shared\Application\Countries\SearchAll\SearchAllCountryResponse;
use Qalis\Shared\Domain\Cities\CityReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use function Lambdish\Phunctional\map;
use stdClass;

class LaravelCityReadRepository implements CityReadRepository
{

    public function searchByCode(string $cityCode, string $provinceCode, string $countryCode): ?CityResponse
    {
        $city = QueryBuilderUtils::notDeleted(DB::table('cities')
            ->join('provinces', 'provinces.id', '=', 'cities.province_id')
            ->join('countries', 'countries.id', '=', 'provinces.country_id'))
            ->where('cities.code1', $cityCode)
            ->where('provinces.code1', $provinceCode)
            ->where('countries.code', $countryCode)
            ->selectRaw('lower(hex(cities.uuid)) as id, lower(hex(provinces.uuid)) as provinceId, cities.name, cities.code1, cities.code2')
            ->first();

        return $city ? new CityResponse(
            $city->id,
            $city->name,
            $city->provinceId,
            $city->code1,
            $city->code2
        ): null;
    }

    public function find(string $cityId): CityResponse
    {

        $city = QueryBuilderUtils::notDeleted(DB::table('cities')
            ->join('provinces', 'provinces.id', '=', 'cities.province_id'))
            ->whereRaw('hex(cities.uuid) = "'.$cityId.'"')
            ->selectRaw('lower(hex(cities.uuid)) as id, lower(hex(provinces.uuid)) as provinceId, cities.name, cities.code1, cities.code2')
            ->first();

        return new CityResponse(
            $city->id,
            $city->name,
            $city->provinceId,
            $city->code1,
            $city->code2
        );

    }

    public function searchByProvince(string $provinceId): SearchCitiesResponse
    {
        $provinces = QueryBuilderUtils::notDeleted(DB::table('cities')
            ->join('provinces', 'provinces.id', '=', 'cities.province_id'))
            ->whereRaw('hex(provinces.uuid) = "'.$provinceId.'"')
            ->selectRaw('lower(hex(cities.uuid)) as id, lower(hex(provinces.uuid)) as provinceId, cities.name, cities.code1, cities.code2')
            ->orderBy('name')
            ->get();

        return new SearchCitiesResponse(...map($this->toResponse(), $provinces));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $city) => new SearchCityResponse(
            $city->id,
            $city->name,
            $city->provinceId,
            $city->code1,
            $city->code2
        );
    }

}
