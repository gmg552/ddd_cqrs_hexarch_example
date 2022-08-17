<?php

namespace Qalis\Shared\Application\Offers\FindToEdit;

final class FindOfferItemToEditResponse {

    private string $id;
    private string $description;
    private float $grossAmount;
    private float $discount;
    private int $units;
    private ?float $finalAmount;
    private ?string $serviceId;

    public function __construct(
        string $id,
        string $description,
        float $grossAmount,
        float $discount,
        int $units,
        ?float $finalAmount,
        ?string $serviceId
    )
    {
        $this->id = $id;
        $this->serviceId = $serviceId;
        $this->description = $description;
        $this->grossAmount = $grossAmount;
        $this->discount = $discount;
        $this->units = $units;
        $this->finalAmount = $finalAmount;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'serviceId' => $this->serviceId,
            'description' => $this->description,
            'grossAmount' => $this->grossAmount,
            'discount' => $this->discount,
            'finalAmount' => $this->finalAmount,
            'units' => $this->units,
        ];
    }

}
