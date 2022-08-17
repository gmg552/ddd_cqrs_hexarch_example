<?php

namespace Qalis\Certification\Advisors\Application\Search;

final class SearchAdvisorsQueryHandler
{
    private AdvisorsSearcher $advisorsSearcher;

    public function __construct(AdvisorsSearcher $advisorsSearcher)
    {
        $this->advisorsSearcher = $advisorsSearcher;
    }

    public function __invoke(SearchAdvisorsQuery $query): SearchAdvisorsResponse
    {
        return $this->advisorsSearcher->__invoke();
    }
}
