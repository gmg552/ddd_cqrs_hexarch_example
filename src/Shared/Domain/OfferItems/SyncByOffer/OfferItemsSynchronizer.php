<?php

namespace Qalis\Shared\Domain\OfferItems\SyncByOffer;

use Qalis\Shared\Domain\OfferItems\OfferItem;
use Qalis\Shared\Domain\OfferItems\OfferItemRepository;

final class OfferItemsSynchronizer {

    private OfferItemRepository $offerItemRepository;

    public function __construct(OfferItemRepository $offerItemRepository)
    {
        $this->offerItemRepository = $offerItemRepository;
    }

    public function __invoke(string $offerId, array $offerItems)
    {

        $this->offerItemRepository->deleteByOffer($offerId);
        $this->setOfferIdToOfferItems($offerId, $offerItems);

        foreach($offerItems as $offerItem) {
            $offerItem = OfferItem::createFromArray($offerItem);
            $this->offerItemRepository->save($offerItem);
        }

    }

    private function setOfferIdToOfferItems(string $offerId, array &$offerItems)
    {
        foreach($offerItems as &$offerItem) {
            $offerItem['offerId'] = $offerId;
        }
    }

}
