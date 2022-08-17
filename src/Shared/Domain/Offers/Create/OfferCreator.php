<?php

namespace Qalis\Shared\Domain\Offers\Create;

use Qalis\Shared\Domain\OfferItems\OfferItemRepository;
use Qalis\Shared\Domain\Offers\Offer;
use Qalis\Shared\Domain\Offers\OfferRepository;

final class OfferCreator {

    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function __invoke(
        string $id,
        string $chargeMethodId,
        string $customerId,
        string $date,
        string $state,
        bool $invoiceRequired,
        float $grossTotal,
        float $netTotal,
        ?bool $invoiceCharged,
        ?string $invoiceCode,
        ?string $invoiceDate,
        ?string $invoiceChargeDate,
        ?string $notes
    )
    {

        $offer = Offer::createFromPrimitives(
            $id,
            $chargeMethodId,
            $customerId,
            $date,
            $state,
            $invoiceRequired,
            $grossTotal,
            $netTotal,
            $invoiceCharged,
            $invoiceCode,
            $invoiceDate,
            $invoiceChargeDate,
            $notes
        );

        $this->offerRepository->save($offer);

    }

}
