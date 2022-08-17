<?php

namespace Qalis\Shared\Domain\Offers\Update;

use Qalis\Certification\Shared\Domain\ChargeMethods\ChargeMethodId;
use Qalis\Certification\Shared\Domain\Offers\OfferId;
use Qalis\Shared\Domain\OfferItems\OfferItemRepository;
use Qalis\Shared\Domain\Offers\OfferInvoiceCode;
use Qalis\Shared\Domain\Offers\OfferNotes;
use Qalis\Shared\Domain\Offers\OfferReadRepository;
use Qalis\Shared\Domain\Offers\OfferRepository;
use Qalis\Shared\Domain\Offers\OfferState;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Domain\ValueObjects\FloatValueObject;

final class OfferUpdater {

    private OfferRepository $offerRepository;
    private OfferReadRepository $offerReadRepository;

    public function __construct(OfferRepository $offerRepository, OfferReadRepository $offerReadRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->offerReadRepository = $offerReadRepository;
    }
    public function __invoke(
        string $id,
        string $chargeMethodId,
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

        $offer = $this->offerRepository->find(new OfferId($id));

        $offer->updateChargeMethodId(new ChargeMethodId($chargeMethodId));
        $offer->updateDate(new DateValueObject($date));
        $offer->updateState(new OfferState($state));
        $offer->updateInvoiceRequired(new BoolValueObject($invoiceRequired));
        $offer->updateGrossTotal(new FloatValueObject($grossTotal));
        $offer->updateNetTotal(new FloatValueObject($netTotal));
        $offer->updateInvoiceCharged($invoiceCharged !== null ? new BoolValueObject($invoiceCharged) : null);
        $offer->updateInvoiceCode($invoiceCode !== null ? new OfferInvoiceCode($invoiceCode) : null);
        $offer->updateInvoiceDate($invoiceDate !== null ? new DateValueObject($invoiceDate) : null);
        $offer->updateInvoiceChargeDate($invoiceChargeDate !== null ? new DateValueObject($invoiceChargeDate) : null);
        $offer->updateNotes($notes !== null ? new OfferNotes($notes) : null);

        $this->offerRepository->save($offer);

    }

}
