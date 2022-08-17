<?php

namespace Qalis\Shared\Application\PrivacyLevels\SearchByScheme;

use Qalis\Shared\Domain\PrivacyLevels\PrivacyLevelReadRepository;

final class PrivacyLevelsSearcher
{
    private PrivacyLevelReadRepository $privacyLevelReadRepository;

    public function __construct(PrivacyLevelReadRepository $privacyLevelReadRepository)
    {
        $this->privacyLevelReadRepository = $privacyLevelReadRepository;
    }

    public function __invoke(string $serviceId): SearchPrivacyLevelsResponse
    {
        return $this->privacyLevelReadRepository->searchByScheme($serviceId);
    }
}
