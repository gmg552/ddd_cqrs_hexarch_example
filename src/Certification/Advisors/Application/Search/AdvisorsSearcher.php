<?php

namespace Qalis\Certification\Advisors\Application\Search;

use Qalis\Certification\Advisors\Domain\AdvisorReadRepository;

final class AdvisorsSearcher {

    private AdvisorReadRepository $advisorReadRepository;

    public function __construct(AdvisorReadRepository $advisorReadRepository)
    {
        $this->advisorReadRepository = $advisorReadRepository;
    }

    public function __invoke(): SearchAdvisorsResponse
    {
        return $this->advisorReadRepository->search();
    }

}
