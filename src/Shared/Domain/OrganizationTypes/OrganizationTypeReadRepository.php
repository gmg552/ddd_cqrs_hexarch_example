<?php

namespace Qalis\Shared\Domain\OrganizationTypes;

use Qalis\Shared\Application\OrganizationTypes\SearchAll\SearchAllOrganizationTypesResponse;

interface OrganizationTypeReadRepository {
    public function searchAll(): SearchAllOrganizationTypesResponse;
}
