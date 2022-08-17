<?php

namespace Qalis\Shared\Application\Cities\SearchByProvince;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchCitiesQuery extends Query {

    private string $provinceId;

    public function __construct(string $provinceId)
    {
        $this->provinceId = $provinceId;
    }

    /**
     * @return string
     */
    public function provinceId(): string
    {
        return $this->provinceId;
    }

}
