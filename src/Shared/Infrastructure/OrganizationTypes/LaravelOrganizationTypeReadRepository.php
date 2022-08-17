<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\OrganizationTypes;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\OrganizationTypes\SearchAll\SearchAllOrganizationTypeResponse;
use Qalis\Shared\Application\OrganizationTypes\SearchAll\SearchAllOrganizationTypesResponse;
use Qalis\Shared\Domain\OrganizationTypes\OrganizationTypeReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use function Lambdish\Phunctional\map;
use stdClass;

class LaravelOrganizationTypeReadRepository implements OrganizationTypeReadRepository
{

    public function searchAll(): SearchAllOrganizationTypesResponse
    {
        $organizationTypes = QueryBuilderUtils::notDeleted(DB::table('organization_types'))->selectRaw('lower(hex(organization_types.uuid)) as id, name, code, entity_type')->get();
        return new SearchAllOrganizationTypesResponse(...map($this->toResponse(), $organizationTypes));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $organizationType) => new SearchAllOrganizationTypeResponse(
            $organizationType->id,
            $organizationType->name,
            $organizationType->code,
            $organizationType->entity_type
        );
    }

}
