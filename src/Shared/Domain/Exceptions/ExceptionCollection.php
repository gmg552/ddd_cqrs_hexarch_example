<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\Exceptions;

use ArrayIterator;
use Countable;
use Exception;
use IteratorAggregate;
use function Lambdish\Phunctional\map;


final class ExceptionCollection extends Exception implements Countable, IteratorAggregate
{

    protected $exceptions;
    public $status = 400;

    public function __construct()
    {
        $this->exceptions = [];
        parent::__construct();
    }

    public function add(Exception $exception){
        array_push($this->exceptions, $exception);
    }

    public function exceptions(): array
    {
        return $this->exceptions;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->exceptions());
    }

    public function count(): int
    {
        return count($this->exceptions());
    }

    public function toArray(): array {
        return map(static fn(Exception $exception) => $exception->getMessage(), $this->exceptions());
    }


}
