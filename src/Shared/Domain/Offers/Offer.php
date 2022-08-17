<?php

namespace Qalis\Shared\Domain\Offers;

use Qalis\Certification\Shared\Domain\ChargeMethods\ChargeMethodId;
use Qalis\Certification\Shared\Domain\Customers\CustomerId;
use Qalis\Certification\Shared\Domain\Offers\OfferId;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Domain\ValueObjects\FloatValueObject;

final class Offer {

    private OfferId $id;
    private CustomerId $customerId;
    private DateValueObject $date;
    private FloatValueObject $grossTotal;
    private FloatValueObject $netTotal;
    private OfferState $state;
    private BoolValueObject $invoiceRequired;
    private ?ChargeMethodId $chargeMethodId;
    private ?OfferInvoiceCode $invoiceCode;
    private ?DateValueObject $invoiceDate;
    private ?BoolValueObject $invoiceCharged;
    private ?DateValueObject $invoiceChargeDate;
    private ?OfferNotes $notes;

    public function __construct(
        OfferId $id,
        CustomerId $customerId,
        DateValueObject $date,
        FloatValueObject $grossTotal,
        FloatValueObject $netTotal,
        OfferState $state,
        BoolValueObject $invoiceRequired,
        ?ChargeMethodId $chargeMethodId = null,
        ?OfferInvoiceCode $invoiceCode = null,
        ?DateValueObject $invoiceDate = null,
        ?BoolValueObject $invoiceCharged = null,
        ?DateValueObject $invoiceChargeDate = null,
        ?OfferNotes $notes = null
    )
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->date = $date;
        $this->grossTotal = $grossTotal;
        $this->netTotal = $netTotal;
        $this->state = $state;
        $this->invoiceRequired = $invoiceRequired;
        $this->chargeMethodId = $chargeMethodId;
        $this->invoiceCode = $invoiceCode;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceCharged = $invoiceCharged;
        $this->invoiceChargeDate = $invoiceChargeDate;
        $this->notes = $notes;
    }

    public static function createFromPrimitives(
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
        return new Offer(
            new OfferId($id),
            new CustomerId($customerId),
            new DateValueObject($date),
            new FloatValueObject($grossTotal),
            new FloatValueObject($netTotal),
            new OfferState($state),
            new BoolValueObject($invoiceRequired),
            new ChargeMethodId($chargeMethodId),
            $invoiceCode ? new OfferInvoiceCode($invoiceCode) : null,
            $invoiceDate ? new DateValueObject($invoiceDate) : null,
            $invoiceCharged !== null ? new BoolValueObject($invoiceCharged) : null,
            $invoiceChargeDate ? new DateValueObject($invoiceChargeDate) : null,
            $notes ? new OfferNotes($notes) : null
        );
    }

    /**
     * @return OfferId
     */
    public function id(): OfferId
    {
        return $this->id;
    }

    /**
     * @return CustomerId
     */
    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return DateValueObject
     */
    public function date(): DateValueObject
    {
        return $this->date;
    }

    /**
     * @return FloatValueObject
     */
    public function grossTotal(): FloatValueObject
    {
        return $this->grossTotal;
    }

    /**
     * @return OfferState
     */
    public function state(): OfferState
    {
        return $this->state;
    }

    /**
     * @return BoolValueObject
     */
    public function invoiceRequired(): BoolValueObject
    {
        return $this->invoiceRequired;
    }

    /**
     * @return ChargeMethodId|null
     */
    public function chargeMethodId(): ?ChargeMethodId
    {
        return $this->chargeMethodId;
    }

    /**
     * @return OfferInvoiceCode|null
     */
    public function invoiceCode(): ?OfferInvoiceCode
    {
        return $this->invoiceCode;
    }

    /**
     * @return DateValueObject|null
     */
    public function invoiceDate(): ?DateValueObject
    {
        return $this->invoiceDate;
    }

    /**
     * @return BoolValueObject|null
     */
    public function invoiceCharged(): ?BoolValueObject
    {
        return $this->invoiceCharged;
    }

    /**
     * @return DateValueObject|null
     */
    public function invoiceChargeDate(): ?DateValueObject
    {
        return $this->invoiceChargeDate;
    }

    /**
     * @return OfferNotes|null
     */
    public function notes(): ?OfferNotes
    {
        return $this->notes;
    }

    /**
     * @param CustomerId $customerId
     */
    public function updateCustomerId(CustomerId $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @param DateValueObject $date
     */
    public function updateDate(DateValueObject $date): void
    {
        $this->date = $date;
    }

    /**
     * @param FloatValueObject $grossTotal
     */
    public function updateGrossTotal(FloatValueObject $grossTotal): void
    {
        $this->grossTotal = $grossTotal;
    }

    /**
     * @param OfferState $state
     */
    public function updateState(OfferState $state): void
    {
        $this->state = $state;
    }

    /**
     * @param BoolValueObject $invoiceRequired
     */
    public function updateInvoiceRequired(BoolValueObject $invoiceRequired): void
    {
        $this->invoiceRequired = $invoiceRequired;
    }

    /**
     * @param ChargeMethodId|null $chargeMethodId
     */
    public function updateChargeMethodId(?ChargeMethodId $chargeMethodId): void
    {
        $this->chargeMethodId = $chargeMethodId;
    }

    /**
     * @param OfferInvoiceCode|null $invoiceCode
     */
    public function updateInvoiceCode(?OfferInvoiceCode $invoiceCode): void
    {
        $this->invoiceCode = $invoiceCode;
    }

    /**
     * @param DateValueObject|null $invoiceDate
     */
    public function updateInvoiceDate(?DateValueObject $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
    }

    /**
     * @param BoolValueObject|null $invoiceCharged
     */
    public function updateInvoiceCharged(?BoolValueObject $invoiceCharged): void
    {
        $this->invoiceCharged = $invoiceCharged;
    }

    /**
     * @param DateValueObject|null $invoiceChargeDate
     */
    public function updateInvoiceChargeDate(?DateValueObject $invoiceChargeDate): void
    {
        $this->invoiceChargeDate = $invoiceChargeDate;
    }

    /**
     * @param OfferNotes|null $notes
     */
    public function updateNotes(?OfferNotes $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return FloatValueObject
     */
    public function netTotal(): FloatValueObject
    {
        return $this->netTotal;
    }

    /**
     * @param FloatValueObject $netTotal
     */
    public function updateNetTotal(FloatValueObject $netTotal): void
    {
        $this->netTotal = $netTotal;
    }


}
