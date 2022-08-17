<?php

namespace Qalis\Shared\Application\Areas\SearchAll;

final class SearchAreaResponse {

    private string $id;
    private string $name;

    public function __construct(
        string $id,
        string $name
    )
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
