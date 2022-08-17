<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Areas;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Areas\SearchAll\SearchAreaResponse;
use Qalis\Shared\Application\Areas\SearchAll\SearchAreasResponse;
use Qalis\Shared\Domain\Areas\AreaReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelAreaReadRepository implements AreaReadRepository
{

    public function searchAll(): SearchAreasResponse
    {
        $areas = QueryBuilderUtils::notDeleted(DB::table('areas'))
            ->selectRaw('lower(hex(areas.uuid)) as id, name')
            ->get();

        return new SearchAreasResponse(...map($this->toResponse(), $areas));

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $area) => new SearchAreaResponse(
            $area->id,
            $area->name
        );
    }

}
