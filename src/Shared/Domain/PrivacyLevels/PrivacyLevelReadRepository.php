<?php

namespace Qalis\Shared\Domain\PrivacyLevels;

use Qalis\Shared\Application\PrivacyLevels\SearchByScheme\SearchPrivacyLevelsResponse;

interface PrivacyLevelReadRepository {
    public function searchByScheme(string $schemeId): SearchPrivacyLevelsResponse;
    public function searchIdByCodeAndScheme(string $privacyLevelCode, string $baseSchemeId): ?string;
}
