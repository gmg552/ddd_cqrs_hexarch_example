<?php

namespace Qalis\Shared\Domain\BatchCommandErrors;

interface Validable {
    public function errors(): BatchCommandErrorsResponse;
}
