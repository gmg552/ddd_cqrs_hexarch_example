<?php

namespace Qalis\Shared\Domain\OrganizationTypes;


final class OrganizationType {

    public const ENTITY_TYPE_NATURAL = 'natural';
    public const ENTITY_TYPE_LEGAL = 'legal';

    private OrganizationTypeId $id;
    private OrganizationTypeName $name;
    private OrganizationTypeCode $code;
    private OrganizationTypeEntityType $entityType;

    public function __construct(
        OrganizationTypeId $id,
        OrganizationTypeName $name,
        OrganizationTypeCode $code,
        OrganizationTypeEntityType $entityType
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->entityType = $entityType;
    }

    /**
     * @return OrganizationTypeId
     */
    public function id(): OrganizationTypeId
    {
        return $this->id;
    }


    /**
     * @return OrganizationTypeName
     */
    public function name(): OrganizationTypeName
    {
        return $this->name;
    }

    /**
     * @param OrganizationTypeName $name
     */
    public function updateName(OrganizationTypeName $name): void
    {
        $this->name = $name;
    }

    /**
     * @return OrganizationTypeCode
     */
    public function code(): OrganizationTypeCode
    {
        return $this->code;
    }

    /**
     * @param OrganizationTypeCode $code
     */
    public function updateCode(OrganizationTypeCode $code): void
    {
        $this->code = $code;
    }

    /**
     * @return OrganizationTypeEntityType
     */
    public function entityType(): OrganizationTypeEntityType
    {
        return $this->entityType;
    }

    /**
     * @param OrganizationTypeEntityType $entityType
     */
    public function updateEntityType(OrganizationTypeEntityType $entityType): void
    {
        $this->entityType = $entityType;
    }




}
