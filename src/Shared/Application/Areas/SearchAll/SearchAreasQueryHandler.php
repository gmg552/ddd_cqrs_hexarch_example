<?php

namespace Qalis\Shared\Application\Areas\SearchAll;

final class SearchAreasQueryHandler
{
    private AreasSearcher $areasSearcher;

    public function __construct(AreasSearcher $areasSearcher)
    {
        $this->areasSearcher = $areasSearcher;
    }

    public function __invoke(): SearchAreasResponse
    {
        return $this->areasSearcher->__invoke();
    }
}

