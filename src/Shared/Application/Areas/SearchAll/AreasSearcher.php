<?php

namespace Qalis\Shared\Application\Areas\SearchAll;

use Qalis\Shared\Domain\Areas\AreaReadRepository;

final class AreasSearcher {

    private AreaReadRepository $areaReadRepository;

    public function __construct(AreaReadRepository $areaReadRepository)
    {
        $this->areaReadRepository = $areaReadRepository;
    }

    public function __invoke(): SearchAreasResponse
    {
        return $this->areaReadRepository->searchAll();
    }

}
