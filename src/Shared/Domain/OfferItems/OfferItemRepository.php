<?php

namespace Qalis\Shared\Domain\OfferItems;

interface OfferItemRepository {
    public function deleteByOffer(string $offerId): void;
    public function save(OfferItem $offerItem): void;
}
