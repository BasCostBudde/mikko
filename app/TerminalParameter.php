<?php

namespace App;

class TerminalParameter implements Parameter
{

    /**
     * @var array<string> $values
     */
    private array $values = [];

    /**
     * @param array<string> $values
     */
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
