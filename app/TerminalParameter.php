<?php

namespace App;

class TerminalParameter implements Parameter
{

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function get(string $name): ?string
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }
        return null;
    }

}
