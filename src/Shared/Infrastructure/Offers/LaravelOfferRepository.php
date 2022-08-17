<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Offers;

use Illuminate\Support\Facades\DB;
use Qalis\Certification\Shared\Domain\ChargeMethods\ChargeMethodId;
use Qalis\Certification\Shared\Domain\Customers\CustomerId;
use Qalis\Certification\Shared\Domain\Offers\OfferId;
use Qalis\Shared\Domain\Offers\Offer;
use Qalis\Shared\Domain\Offers\OfferInvoiceCode;
use Qalis\Shared\Domain\Offers\OfferNotes;
use Qalis\Shared\Domain\Offers\OfferRepository;
use Qalis\Shared\Domain\Offers\OfferState;
use Qalis\Shared\Domain\ValueObjects\BoolValueObject;
use Qalis\Shared\Domain\ValueObjects\DateValueObject;
use Qalis\Shared\Domain\ValueObjects\FloatValueObject;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Offer as EloquentOffer;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelOfferRepository implements OfferRepository
{

    public function find(OfferId $id): Offer
    {

        $offer = DB::table('offers')
            ->join('customers', 'customers.id', '=', 'offers.customer_id')
            ->leftJoin('charge_methods', 'charge_methods.id', '=', 'offers.charge_method_id')
            ->selectRaw('lower(hex(offers.uuid)) as offerId,
            lower(hex(customers.uuid)) as customerId,
            lower(hex(charge_methods.uuid)) as chargeMethodId, gross_total, net_total, state, invoice_required,
            invoice_code, invoice_date, invoice_charged, date, invoice_charge_date, notes')
            ->first();

        return new Offer(
            new OfferId($offer->offerId),
            new CustomerId($offer->customerId),
            new DateValueObject($offer->date),
            new FloatValueObject((float)$offer->gross_total),
            new FloatValueObject((float)$offer->net_total),
            new OfferState($offer->state),
            new BoolValueObject((bool)$offer->invoice_required),
            $offer->chargeMethodId ? new ChargeMethodId($offer->chargeMethodId) : null,
            $offer->invoice_code ? new OfferInvoiceCode($offer->invoice_code): null,
            $offer->invoice_date ? new DateValueObject($offer->invoice_date): null,
            $offer->invoice_charged !== null ? new BoolValueObject((bool)$offer->invoice_charged): null,
            $offer->invoice_charge_date ? new DateValueObject($offer->invoice_charge_date): null,
            $offer->notes ? new OfferNotes($offer->notes): null
        );
    }

    public function save(Offer $offer): void
    {
        EloquentOffer::updateOrCreate(
            [
                'uuid' => $offer->id()->binValue()
            ],
            [
                'customer_id' => $offer->customerId() ? Uuid2Id::resolve('customers', $offer->customerId()->value()) : null,
                'charge_method_id' => $offer->chargeMethodId() ? Uuid2Id::resolve('charge_methods', $offer->chargeMethodId()->value()) : null,
                'date' => $offer->date(),
                'gross_total' => $offer->grossTotal()->value(),
                'net_total' => $offer->netTotal()->value(),
                'state' => $offer->state(),
                'invoice_required' => $offer->invoiceRequired()->value(),
                'invoice_code' => $offer->invoiceCode(),
                'invoice_date' => $offer->invoiceDate(),
                'invoice_charged' => $offer->invoiceCharged() !== null ? $offer->invoiceCharged()->value() : null,
                'invoice_charge_date' => $offer->invoiceChargeDate(),
                'notes' => $offer->notes()
            ]
        );
    }

    public function delete(string $offerId): void
    {
        DB::table('offers')
            ->whereRaw('hex(offers.uuid) = "'.$offerId.'"')
            ->delete();
    }
}
