<?php

namespace Qalis\Shared\Application\OrganizationTypes\SearchAll;

final class SearchAllOrganizationTypesQueryHandler
{
    private OrganizationTypesSearcher $organizationTypesSearcher;

    public function __construct(OrganizationTypesSearcher $organizationTypesSearcher)
    {
        $this->organizationTypesSearcher = $organizationTypesSearcher;
    }

    public function __invoke(): SearchAllOrganizationTypesResponse
    {
        return $this->organizationTypesSearcher->__invoke();
    }
}
