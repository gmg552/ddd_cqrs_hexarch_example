<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Offers;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Offers\FindToEdit\FindOfferItemToEditResponse;
use Qalis\Shared\Application\Offers\FindToEdit\FindOfferToEditResponse;
use Qalis\Shared\Domain\Offers\OfferReadRepository;

class LaravelOfferReadRepository implements OfferReadRepository
{

    public function findToEdit(string $offerId): FindOfferToEditResponse
    {
        $offer = DB::table('offers')
            ->leftJoin('charge_methods', 'charge_methods.id', '=', 'offers.charge_method_id')
            ->whereRaw('hex(offers.uuid) = "'.$offerId.'"')
            ->selectRaw('lower(hex(offers.uuid)) as offerId,
            lower(hex(charge_methods.uuid)) as chargeMethodId,
            offers.date, offers.gross_total, offers.net_total, offers.state, offers.invoice_charged, offers.invoice_date, offers.invoice_code,
            offers.invoice_charge_date, offers.notes, offers.invoice_required')
            ->first();

        $offerItems = DB::table('offer_items')
            ->join('offers', 'offers.id', '=', 'offer_items.offer_id')
            ->leftJoin('services', 'services.id', '=', 'offer_items.service_id')
            ->whereRaw('hex(offers.uuid) = "'.$offerId.'"')
            ->selectRaw('lower(hex(offer_items.uuid)) as id,
            lower(hex(services.uuid)) as serviceId,
            description, gross_amount, final_amount, discount, units')
            ->get();

        $offerItemsCollection = [];

        foreach($offerItems as $offerItem) {
            array_push($offerItemsCollection,
            new FindOfferItemToEditResponse(
                $offerItem->id,
                $offerItem->description,
                (float)$offerItem->gross_amount,
                (float)$offerItem->discount,
                $offerItem->units,
                null,
                $offerItem->serviceId
            ));
        }

        return new FindOfferToEditResponse(
            $offer->offerId,
            $offer->date,
            (float)$offer->gross_total,
            $offer->state,
            (bool)$offer->invoice_required,
            $offer->chargeMethodId,
            $offer->invoice_code,
            $offer->invoice_date,
            (bool)$offer->invoice_charged,
            $offer->invoice_charge_date,
            $offer->notes,
            ...$offerItemsCollection
        );

    }

}
