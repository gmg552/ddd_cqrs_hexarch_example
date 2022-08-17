<?php

namespace Qalis\Shared\Application\Provinces\Find;

final class FindProvinceResponse
{

    private string $id;
    private string $name;
    private string $countryId;
    private ?string $code1;
    private ?string $code2;

    public function __construct(string $id, string $name, string $countryId, ?string $code1 = null, ?string $code2 = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->code1 = $code1;
        $this->code2 = $code2;
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
    public function countryId(): string
    {
        return $this->countryId;
    }

    /**
     * @return string|null
     */
    public function code1(): ?string
    {
        return $this->code1;
    }

    /**
     * @return string|null
     */
    public function code2(): ?string
    {
        return $this->code2;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'countryId' => $this->countryId,
            'code1' => $this->code1,
            'code2' => $this->code2
        ];
    }

}
