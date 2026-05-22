<?php

namespace App;

interface Parameter
{

    public function get(string $name): ?string;

}
