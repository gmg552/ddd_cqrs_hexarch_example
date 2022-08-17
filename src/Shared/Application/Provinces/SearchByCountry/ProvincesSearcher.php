<?php

namespace Qalis\Shared\Application\Provinces\SearchByCountry;

use Qalis\Shared\Domain\Provinces\ProvinceReadRepository;

final class ProvincesSearcher
{
    private ProvinceReadRepository $provinceReadRepository;

    public function __construct(ProvinceReadRepository $provinceReadRepository)
    {
        $this->provinceReadRepository = $provinceReadRepository;
    }

    public function __invoke(string $countryId): SearchProvincesResponse
    {
        return $this->provinceReadRepository->searchByCountry($countryId);
    }
}
