<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\OfferItems;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\OfferItems\OfferItem;
use Qalis\Shared\Domain\OfferItems\OfferItemRepository;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OfferItem as EloquentOfferItem;

class LaravelOfferItemRepository implements OfferItemRepository
{

    public function save(OfferItem $offerItem): void
    {
        EloquentOfferItem::updateOrCreate(
            [
                'uuid' => $offerItem->id()->binValue()
            ],
            [
                'description' => $offerItem->description(),
                'offer_id' => Uuid2Id::resolve('offers', $offerItem->offerId()->value()),
                'service_id' => $offerItem->serviceId() ? Uuid2Id::resolve('services', $offerItem->serviceId()->value()) : null,
                'final_amount' => $offerItem->finalAmount()->value(),
                'gross_amount' => $offerItem->grossAmount()->value(),
                'discount' => $offerItem->discount()->value(),
                'units' => $offerItem->units()->value(),
            ]
        );
    }

    public function deleteByOffer(string $offerId): void
    {
        $offerId = Uuid2Id::resolve('offers', $offerId);

        DB::table('offer_items')
            ->where('offer_id', $offerId)
            ->delete();

    }

}
