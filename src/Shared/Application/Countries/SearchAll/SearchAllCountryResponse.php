<?php

namespace Qalis\Shared\Application\Countries\SearchAll;

final class SearchAllCountryResponse {

    private string $id;
    private string $name;
    private string $code;

    public function __construct(
        string $id,
        string $name,
        string $code
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
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

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code
        ];
    }

}
