<?php

namespace Qalis\Shared\Application\Offers\FindToEdit;

final class FindOfferQueryHandler
{
    private OfferFinder $offerFinder;

    public function __construct(OfferFinder $offerFinder)
    {
        $this->offerFinder = $offerFinder;
    }

    public function __invoke(FindOfferQuery $query): FindOfferToEditResponse
    {
        return $this->offerFinder->__invoke($query->id());
    }
}
