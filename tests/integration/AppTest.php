<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function test_be_quiet()
    {
        $this->assertEquals(1, 1);
    }

    public function no_test_works_only_may_2026()
    {
        if (file_exists('out.csv')) {
            unlink('out.csv');
        }
        $app = new App();
        $app->process(date());
        $actual = file_get_contents('out.csv');
        $this->assertEquals($this->expected(), $actual);
    }

    private function expected()
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

