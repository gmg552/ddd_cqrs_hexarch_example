<?php

namespace Qalis\Certification\Advisors\Application\Search;

final class SearchAdvisorResponse {

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

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
