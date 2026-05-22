<?php

use PHPUnit\Framework\TestCase;

class PocTest extends TestCase
{

    public function test_works_only_may_2026(): void
    {
        if (file_exists('out.csv')) {
            unlink('out.csv');
        }
        $argc = 0;
        include "poc.php";
        $actual = file_get_contents('out.csv');
        $this->assertEquals($this->expected(), $actual);
    }

    private function expected(): string
    {
        return <<<TXT
May,2026-05-29,2026-05-15
June,2026-06-30,2026-06-15
July,2026-07-31,2026-07-15
August,2026-08-31,2026-08-19
September,2026-09-30,2026-09-15
October,2026-10-30,2026-10-15
November,2026-11-30,2026-11-18
December,2026-12-31,2026-12-15

TXT;
    }

}
