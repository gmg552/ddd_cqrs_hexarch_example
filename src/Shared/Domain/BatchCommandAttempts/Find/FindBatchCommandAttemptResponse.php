<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Certification\AuditChecklists\Application\SearchNoSiteResources\NoSiteChecklistSchemeResponse;
use Qalis\Shared\Domain\Bus\Query\Response;
use function Lambdish\Phunctional\map;

class FindBatchCommandAttemptResponse implements Response {

    private string $id;
    private string $createdAt;
    private FindBatchCommandErrorsResponse $errors;

    public function __construct(
        string $id,
        string $createdAt,
        FindBatchCommandErrorsResponse $errors
    )
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->errors = $errors;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'errors' => map(static fn(FindBatchCommandErrorResponse $item) => $item->toArray(), $this->errors)
        ];
    }



}
