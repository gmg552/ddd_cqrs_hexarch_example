<?php

namespace Qalis\Certification\Shared\Application\OutputDocuments\Search;

use Qalis\Shared\Domain\Bus\Query\Response;

class SearchOutputDocumentResponse implements Response
{
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

    public function toArray() : array {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
