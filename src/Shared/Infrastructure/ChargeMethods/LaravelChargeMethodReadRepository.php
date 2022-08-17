<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\ChargeMethods;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\ChargeMethods\SearchAll\SearchAllChargeMethodResponse;
use Qalis\Shared\Application\ChargeMethods\SearchAll\SearchAllChargeMethodsResponse;
use Qalis\Shared\Domain\ChargeMethods\ChargeMethodReadRepository;
use stdClass;
use function Lambdish\Phunctional\map;

final class LaravelChargeMethodReadRepository implements ChargeMethodReadRepository
{

    public function searchAll(): SearchAllChargeMethodsResponse
    {

        $chargeMethods = DB::table('charge_methods')
            ->selectRaw('lower(hex(charge_methods.uuid)) as id, name')
            ->get();

        return new SearchAllChargeMethodsResponse(...map($this->toResponse(), $chargeMethods));

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $city) => new SearchAllChargeMethodResponse(
            $city->id,
            $city->name
        );
    }

}
