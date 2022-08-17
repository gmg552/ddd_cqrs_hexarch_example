<?php

namespace Qalis\Shared\Domain\Bus;

use Qalis\Shared\Domain\Bus\Query\Response;

final class EmptyResponse implements Response {

    public function toArray(): array {
        return [];
    }

}
