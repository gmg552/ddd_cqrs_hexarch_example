<?php

namespace Qalis\Shared\Application\PrivacyLevels\SearchByScheme;

final class SearchPrivacyLevelResponse {

    private string $id;
    private string $serviceId;
    private string $label;
    private string $description;

    public function __construct(
        string $id,
        string $serviceId,
        string $label,
        string $description
    ) {
        $this->id = $id;
        $this->serviceId = $serviceId;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function serviceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'serviceId' => $this->serviceId,
            'label' => $this->label,
            'description' => $this->description
        ];
    }

}
