<?php

namespace Qalis\Shared\Application\Cities\SearchByProvince;

use Qalis\Shared\Domain\Cities\CityReadRepository;

final class CitiesSearcher
{
    private CityReadRepository $cityReadRepository;

    public function __construct(CityReadRepository $cityReadRepository)
    {
        $this->cityReadRepository = $cityReadRepository;
    }

    public function __invoke(string $provinceId): SearchCitiesResponse
    {
        return $this->cityReadRepository->searchByProvince($provinceId);
    }
}
