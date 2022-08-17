<?php

namespace Qalis\Shared\Application\OrganizationTypes\SearchAll;

use Qalis\Shared\Domain\OrganizationTypes\OrganizationTypeReadRepository;

final class OrganizationTypesSearcher
{
    private OrganizationTypeReadRepository $organizationTypeReadRepository;

    public function __construct(OrganizationTypeReadRepository $organizationTypeReadRepository)
    {
        $this->organizationTypeReadRepository = $organizationTypeReadRepository;
    }

    public function __invoke(): SearchAllOrganizationTypesResponse
    {
        return $this->organizationTypeReadRepository->searchAll();
    }
}
