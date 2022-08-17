<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Countries;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Countries\SearchAll\SearchAllCountriesResponse;
use Qalis\Shared\Application\Countries\SearchAll\SearchAllCountryResponse;
use Qalis\Shared\Domain\Countries\CountryReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use function Lambdish\Phunctional\map;
use stdClass;

class LaravelCountryReadRepository implements CountryReadRepository
{

    public function getCountryIdByCode(string $countryCode): ?string
    {
        return QueryBuilderUtils::notDeleted(DB::table('countries'))
            ->where('code', $countryCode)
            ->selectRaw('lower(hex(countries.uuid)) as id')
            ->pluck('id')
            ->first();
    }

    public function searchAll(): SearchAllCountriesResponse
    {
        $countries = QueryBuilderUtils::notDeleted(DB::table('countries'))
            ->selectRaw('lower(hex(countries.uuid)) as id, name, code')
            ->orderBy('name')
            ->get();
        return new SearchAllCountriesResponse(...map($this->toResponse(), $countries));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $country) => new SearchAllCountryResponse(
            $country->id,
            $country->name,
            $country->code
        );
    }

}
