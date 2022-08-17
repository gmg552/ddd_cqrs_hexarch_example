<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Search;

final class SearchSpreadsheetBatchCommandResponse
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


    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
