<?php

namespace Qalis\Shared\Domain\Offers;

use Qalis\Certification\Shared\Domain\Offers\OfferId;

interface OfferRepository {
    public function delete(string $offerId): void;
    public function save(Offer $offer): void;
    public function find(OfferId $id): Offer;
}
