<?php

namespace Qalis\Shared\Application\Provinces\SearchByCountry;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchProvincesQuery extends Query {

    private string $countryId;

    public function __construct(string $countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * @return string
     */
    public function countryId(): string
    {
        return $this->countryId;
    }

}
