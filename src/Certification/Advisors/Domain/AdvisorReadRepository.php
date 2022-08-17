<?php

namespace Qalis\Certification\Advisors\Domain;

use Qalis\Certification\Advisors\Application\Search\SearchAdvisorsResponse;

interface AdvisorReadRepository {
    public function search() : SearchAdvisorsResponse;
}
