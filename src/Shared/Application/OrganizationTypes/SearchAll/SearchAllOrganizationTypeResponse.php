<?php

namespace Qalis\Shared\Application\OrganizationTypes\SearchAll;

final class SearchAllOrganizationTypeResponse {

    private string $id;
    private string $name;
    private string $code;
    private string $entityType;

    public function __construct(
        string $id,
        string $name,
        string $code,
        string $entityType
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->entityType = $entityType;
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
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function entityType(): string
    {
        return $this->entityType;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'entityType' => $this->entityType
        ];
    }

}
