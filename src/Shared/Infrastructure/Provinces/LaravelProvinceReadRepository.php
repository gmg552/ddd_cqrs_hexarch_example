<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Provinces;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Provinces\Find\FindProvinceResponse;
use Qalis\Shared\Application\Provinces\SearchByCountry\SearchProvinceResponse;
use Qalis\Shared\Application\Provinces\SearchByCountry\SearchProvincesResponse;
use Qalis\Shared\Domain\Provinces\ProvinceReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use function Lambdish\Phunctional\map;
use stdClass;

class LaravelProvinceReadRepository implements ProvinceReadRepository
{

    public function find(string $provinceId): FindProvinceResponse
    {
        $province = QueryBuilderUtils::notDeleted(DB::table('provinces')
            ->join('countries', 'countries.id', '=', 'provinces.country_id'))
            ->whereRaw('hex(provinces.uuid) = "'.$provinceId.'"')
            ->selectRaw('lower(hex(provinces.uuid)) as id, lower(hex(countries.uuid)) as countryId, provinces.name, provinces.code1, provinces.code2')
            ->first();

        return new FindProvinceResponse(
            $province->id,
            $province->name,
            $province->countryId,
            $province->code1,
            $province->code2
        );
    }

    public function searchByCountry(string $countryId): SearchProvincesResponse
    {

        $provinces = QueryBuilderUtils::notDeleted(DB::table('provinces')
        ->join('countries', 'countries.id', '=', 'provinces.country_id'))
            ->whereRaw('hex(countries.uuid) = "'.$countryId.'"')
            ->selectRaw('lower(hex(provinces.uuid)) as id, lower(hex(countries.uuid)) as countryId, provinces.name, provinces.code1, provinces.code2')
            ->orderBy('name')
            ->get();

        return new SearchProvincesResponse(...map($this->toResponse(), $provinces));

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $province) => new SearchProvinceResponse(
            $province->id,
            $province->name,
            $province->countryId,
            $province->code1,
            $province->code2
        );
    }

}
