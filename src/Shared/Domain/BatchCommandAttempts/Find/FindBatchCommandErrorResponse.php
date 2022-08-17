<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Shared\Domain\Bus\Query\Response;

class FindBatchCommandErrorResponse implements Response {

    private ?int $index;
    private ?string $message;
    private ?string $howToFix;

    public function __construct(
        ?int $index = null,
        ?string $message = null,
        ?string $howToFix = null
    )
    {
        $this->index = $index;
        $this->message = $message;
        $this->howToFix = $howToFix;
    }

    /**
     * @return int|null
     */
    public function index(): ?int
    {
        return $this->index;
    }

    /**
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function howToFix(): ?string
    {
        return $this->howToFix;
    }


    public function toArray(): array {
        return [
            'index' => $this->index,
            'message' => $this->message,
            'howToFix' => $this->howToFix
        ];
    }


}
