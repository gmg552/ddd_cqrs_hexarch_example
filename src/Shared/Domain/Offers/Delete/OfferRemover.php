<?php

namespace Qalis\Shared\Domain\Offers\Delete;

use Qalis\Shared\Domain\OfferItems\OfferItemRepository;
use Qalis\Shared\Domain\Offers\OfferRepository;

final class OfferRemover {

    private OfferRepository $offerRepository;
    private OfferItemRepository $offerItemRepository;

    public function __construct(
        OfferRepository $offerRepository,
        OfferItemRepository $offerItemRepository
    )
    {
        $this->offerRepository = $offerRepository;
        $this->offerItemRepository = $offerItemRepository;
    }

    public function __invoke(string $offerId)
    {
        $this->offerItemRepository->deleteByOffer($offerId);
        $this->offerRepository->delete($offerId);
    }

}
