<?php

namespace Qalis\Shared\Domain\OfferItems;

use InvalidArgumentException;
use Qalis\Certification\Shared\Domain\OfferItems\OfferItemId;
use Qalis\Certification\Shared\Domain\Offers\OfferId;
use Qalis\Shared\Domain\Services\ServiceId;
use Qalis\Shared\Domain\ValueObjects\FloatValueObject;
use Qalis\Shared\Domain\ValueObjects\IntValueObject;
use Qalis\Shared\Domain\ValueObjects\Uuid;

final class OfferItem {

    private OfferItemId $id;
    private OfferId $offerId;
    private OfferItemDescription $description;
    private FloatValueObject $finalAmount;
    private FloatValueObject $grossAmount;
    private FloatValueObject $discount;
    private IntValueObject $units;
    private ?ServiceId $serviceId;

    public function __construct(
        OfferItemId $id,
        OfferId $offerId,
        OfferItemDescription $description,
        FloatValueObject $finalAmount,
        FloatValueObject $grossAmount,
        FloatValueObject $discount,
        IntValueObject $units,
        ?ServiceId $serviceId = null
    )
    {
        $this->id = $id;
        $this->offerId = $offerId;
        $this->description = $description;
        $this->finalAmount = $finalAmount;
        $this->grossAmount = $grossAmount;
        $this->discount = $discount;
        $this->units = $units;
        $this->serviceId = $serviceId;
    }

    public static function createFromPrimitives(
        string $id,
        string $offerId,
        string $description,
        string $finalAmount,
        string $grossAmount,
        string $discount,
        string $units,
        string $serviceId
    ): OfferItem {
        return new OfferItem(
            new OfferItemId($id),
            new OfferId($offerId),
            new OfferItemDescription($description),
            new FloatValueObject($finalAmount),
            new FloatValueObject($grossAmount),
            new FloatValueObject($discount),
            new IntValueObject($units),
            $serviceId ? new ServiceId($serviceId) : null
        );
    }

    public static function createFromArray(array $offerItem): OfferItem
    {
        self::ensureHasFields($offerItem);

        $grossAmount = $offerItem['grossAmount'] * $offerItem['units'];
        $offerItem['finalAmount'] = $grossAmount - ($grossAmount * ($offerItem['discount']/100));

        return new OfferItem(
            new OfferItemId(Uuid::generateString()),
            new OfferId($offerItem['offerId']),
            new OfferItemDescription($offerItem['description']),
            new FloatValueObject($offerItem['finalAmount']),
            new FloatValueObject($offerItem['grossAmount']),
            new FloatValueObject($offerItem['discount']),
            new IntValueObject($offerItem['units']),
            $offerItem['serviceId'] ? new ServiceId($offerItem['serviceId']) : null
        );
    }



    private static function ensureHasFields($offerItem) {


        if ((!isset($offerItem['description'])) || (!$offerItem['description']))
            throw new InvalidArgumentException("No se encuentra el campo Descripción en la lista de conceptos de la oferta");

        if ((!isset($offerItem['grossAmount'])) || ($offerItem['grossAmount'] === null))
            throw new InvalidArgumentException("No se encuentra el campo Importe Bruto en la lista de conceptos de la oferta");

        if ((!isset($offerItem['discount'])) || ($offerItem['discount'] === null))
            throw new InvalidArgumentException("No se encuentra el campo Descuento en la lista de conceptos de la oferta");

        if ((!isset($offerItem['units'])) || ($offerItem['units']  === null))
            throw new InvalidArgumentException("No se encuentra el campo Unidades en la lista de conceptos de la oferta");

    }

    /**
     * @return OfferItemId
     */
    public function id(): OfferItemId
    {
        return $this->id;
    }

    /**
     * @return OfferId
     */
    public function offerId(): OfferId
    {
        return $this->offerId;
    }

    /**
     * @return OfferItemDescription
     */
    public function description(): OfferItemDescription
    {
        return $this->description;
    }

    /**
     * @return FloatValueObject
     */
    public function finalAmount(): FloatValueObject
    {
        return $this->finalAmount;
    }

    /**
     * @return FloatValueObject
     */
    public function grossAmount(): FloatValueObject
    {
        return $this->grossAmount;
    }

    /**
     * @return FloatValueObject
     */
    public function discount(): FloatValueObject
    {
        return $this->discount;
    }

    /**
     * @return IntValueObject
     */
    public function units(): IntValueObject
    {
        return $this->units;
    }

    /**
     * @return ServiceId|null
     */
    public function serviceId(): ?ServiceId
    {
        return $this->serviceId;
    }

    /**
     * @param OfferId $offerId
     */
    public function updateOfferId(OfferId $offerId): void
    {
        $this->offerId = $offerId;
    }

    /**
     * @param OfferItemDescription $description
     */
    public function updateDescription(OfferItemDescription $description): void
    {
        $this->description = $description;
    }

    /**
     * @param FloatValueObject $finalAmount
     */
    public function updateFinalAmount(FloatValueObject $finalAmount): void
    {
        $this->finalAmount = $finalAmount;
    }

    /**
     * @param FloatValueObject $grossAmount
     */
    public function updateGrossAmount(FloatValueObject $grossAmount): void
    {
        $this->grossAmount = $grossAmount;
    }

    /**
     * @param FloatValueObject $discount
     */
    public function updateDiscount(FloatValueObject $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @param IntValueObject $units
     */
    public function updateUnits(IntValueObject $units): void
    {
        $this->units = $units;
    }

    /**
     * @param ServiceId|null $serviceId
     */
    public function updateServiceId(?ServiceId $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

}
