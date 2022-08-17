<?php

namespace Qalis\Shared\Domain\Bus\Command;

use ReflectionClass;

class Command
{

    public function toArray(): array {
        $args = [];
        $childCommandClass  = get_called_class();
        $reflection = new ReflectionClass($childCommandClass);
        $params = $reflection->getConstructor()->getParameters();
        foreach($params as $param) {
            $args[$param->name] = $this->{$param->name}();
        }
        return $args;
    }

    public static function fromArray(array $array)  {
        $args = [];
        $childCommandClass  = get_called_class();
        $reflection = new ReflectionClass($childCommandClass);
        $params = $reflection->getConstructor()->getParameters();
        foreach ($params as $param) {
            if(!is_array($array[$param->name]))
            array_push($args, $array[$param->name]);
        }
        return new static(...$args);
    }

}
