<?php

namespace Qalis\Shared\Application\PrivacyLevels\SearchByScheme;

final class SearchPrivacyLevelsQueryHandler
{
    private PrivacyLevelsSearcher $privacyLevelsSearcher;

    public function __construct(PrivacyLevelsSearcher $privacyLevelsSearcher)
    {
        $this->privacyLevelsSearcher = $privacyLevelsSearcher;
    }

    public function __invoke(SearchPrivacyLevelsQuery $query): SearchPrivacyLevelsResponse
    {
        return $this->privacyLevelsSearcher->__invoke($query->serviceId());
    }
}
