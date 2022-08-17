<?php

namespace Qalis\Shared\Application\Offers\FindToEdit;

use Qalis\Shared\Domain\Offers\OfferReadRepository;

final class OfferFinder {

    private OfferReadRepository $offerReadRepository;

    public function __construct(OfferReadRepository $offerReadRepository)
    {
        $this->offerReadRepository = $offerReadRepository;
    }

    public function __invoke(string $offerId): FindOfferToEditResponse
    {
        return $this->offerReadRepository->findToEdit($offerId);
    }

}
