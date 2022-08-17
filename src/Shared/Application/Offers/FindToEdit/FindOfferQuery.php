<?php

namespace Qalis\Shared\Application\Offers\FindToEdit;

use Qalis\Shared\Domain\Bus\Query\Query;

class FindOfferQuery extends Query {

    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

}
