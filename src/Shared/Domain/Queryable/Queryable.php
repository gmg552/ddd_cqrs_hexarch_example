<?php

namespace Qalis\Shared\Domain\Queryable;

use Qalis\Shared\Domain\Queryable\QueryableExpression;

interface Queryable {

    public function where(QueryableExpression $expression) : Queryable;
    public function count() : int;
    public function toArray() : array;
    
}
