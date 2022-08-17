<?php

namespace Qalis\Shared\Application\PrivacyLevels\SearchByScheme;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchPrivacyLevelsQuery extends Query {

    private string $serviceId;

    public function __construct(string $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return string
     */
    public function serviceId(): string
    {
        return $this->serviceId;
    }

}
