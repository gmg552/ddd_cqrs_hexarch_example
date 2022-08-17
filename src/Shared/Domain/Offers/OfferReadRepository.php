<?php

namespace Qalis\Shared\Domain\Offers;

use Qalis\Shared\Application\Offers\FindToEdit\FindOfferToEditResponse;

interface OfferReadRepository {
    public function findToEdit(string $offerId) : FindOfferToEditResponse;
}
