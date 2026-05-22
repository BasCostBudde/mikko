<?php

use PHPUnit\Framework\TestCase;
use App\CsvFormatter;

class CsvFormatterTest extends TestCase
{

    public function test_puts_fields_between_commata(): void
    {
        $sut = new CsvFormatter();
        $actual = $sut->format([[1, 2, 3]]);
        $this->assertSame('1,2,3'.PHP_EOL, $actual);
    }

    public function test_puts_string_with_comma_inside_quotes(): void
    {
        $sut = new CsvFormatter();
        $actual = $sut->format([[1, 'kaas,appel', 3]]);
        $this->assertSame('1,"kaas,appel",3'.PHP_EOL, $actual);
    }

    public function test_doubles_quote_inside_string(): void
    {
        $sut = new CsvFormatter();
        $actual = $sut->format([[1, 'kaas",appel', 3]]);
        $this->assertSame('1,"kaas"",appel",3'.PHP_EOL, $actual);
    }

}
