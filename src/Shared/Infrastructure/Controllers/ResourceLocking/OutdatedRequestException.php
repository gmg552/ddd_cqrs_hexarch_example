<?php

namespace Qalis\Shared\Infrastructure\Controllers\ResourceLocking;

use InvalidArgumentException;

final class OutdatedRequestException extends InvalidArgumentException {

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
