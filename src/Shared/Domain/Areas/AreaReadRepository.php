<?php

namespace Qalis\Shared\Domain\Areas;

use Qalis\Shared\Application\Areas\SearchAll\SearchAreasResponse;

interface AreaReadRepository {
    public function searchAll(): SearchAreasResponse;
}
