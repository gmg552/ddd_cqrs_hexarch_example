<?php

namespace Qalis\Shared\Domain\Customers;

interface CustomerRepository {
    public function makeCustomer(string $subjectId): void;
}
