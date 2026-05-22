<?php

use PHPUnit\Framework\TestCase;
use App\PaySchedule;

class PayScheduleTest extends TestCase
{

    public function test_has_twelve_rows_for_full_year()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-01-01');
        $this->assertCount(12, $actual);
    }

    public function test_has_seven_rows_from_june()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-06-01');
        $this->assertCount(7, $actual);
    }

    public function test_row_has_three_fields()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-01-01');
        $this->assertCount(3, $actual[1]);
    }

    public function test_first_field_is_month_name()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-01-01');
        $this->assertSame('February', $actual[1][0]);
    }

    public function test_second_field_is_payday()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-01-01');
        // february 2020 happens to end in week day
        $this->assertSame('2020-02-28', $actual[1][1]);
    }

    public function test_third_field_is_payday()
    {
        $sut = new PaySchedule();
        $actual = $sut->get('2020-01-01');
        // 15 february 2020 happens to be saturday
        $this->assertSame('2020-02-19', $actual[1][2]);
    }

}
