<?php

namespace App;

interface Formatter
{

    // I wish to consult collegues on what type to specify here
    public function format(array $input): string;

}
