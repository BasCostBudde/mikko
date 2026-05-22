<?php

use PHPUnit\Framework\TestCase;
use App\TerminalParameter;

class TerminalParameterTest extends TestCase
{

    public function test_returns_null_if_parameter_absent(): void
    {
        $sut = new TerminalParameter();
        $actual = $sut->get('1');
        $this->assertSame(null, $actual);
    }

    public function test_returns_value_if_parameter_present(): void
    {
        $sut = new TerminalParameter(['program_name', 'value']);
        $actual = $sut->get('1');
        $this->assertSame('value', $actual);
    }

}
