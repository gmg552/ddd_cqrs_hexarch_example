<?php

namespace Qalis\Shared\Application\Offers\FindToEdit;

use Qalis\Shared\Domain\Bus\Query\Response;
use function Lambdish\Phunctional\map;

final class FindOfferToEditResponse implements Response {

    private string $id;
    private string $date;
    private float $grossTotal;
    private string $state;
    private bool $invoiceRequired;
    private ?string $chargeMethodId;
    private ?string $invoiceCode;
    private ?string $invoiceDate;
    private ?bool $invoiceCharged;
    private ?string $invoiceChargeDate;
    private ?string $notes;
    private array $offerItems;

    public function __construct(
        string $id,
        string $date,
        float $grossTotal,
        string $state,
        bool $invoiceRequired,
        ?string $chargeMethodId = null,
        ?string $invoiceCode = null,
        ?string $invoiceDate = null,
        ?bool $invoiceCharged = null,
        ?string $invoiceChargeDate = null,
        ?string $notes = null,
        FindOfferItemToEditResponse ...$offerItems
    )
    {
        $this->id = $id;
        $this->chargeMethodId = $chargeMethodId;
        $this->date = $date;
        $this->grossTotal = $grossTotal;
        $this->state = $state;
        $this->invoiceCode = $invoiceCode;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceCharged = $invoiceCharged;
        $this->invoiceChargeDate = $invoiceChargeDate;
        $this->notes = $notes;
        $this->offerItems = $offerItems;
        $this->invoiceRequired = $invoiceRequired;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'chargeMethodId' => $this->chargeMethodId,
            'date' => $this->date,
            'grossTotal' => $this->grossTotal,
            'state' => $this->state,
            'invoiceRequired' => $this->invoiceRequired,
            'invoiceCharged' => $this->invoiceCharged,
            'invoiceCode' => $this->invoiceCode,
            'invoiceDate' => $this->invoiceDate,
            'invoiceChargeDate' => $this->invoiceChargeDate,
            'notes' => $this->notes,
            'offerItems' => map(static fn(FindOfferItemToEditResponse $item) => $item->toArray(), $this->offerItems)
        ];
    }

}
