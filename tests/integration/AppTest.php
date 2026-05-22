<?php

// dit hoort niet zo natuurlijk, maar het scheelt me een bak use-statements
namespace App;

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function test_sample_output_works_only_may_2026()
    {
        if (file_exists('out.csv')) {
            unlink('out.csv');
        }
        $filesystem = new ProductionFileSystem();
        // no parameter, so should write default file
        $parameter = new TerminalParameter();
        $formatter = new CsvFormatter();
        $schedule = new PaySchedule();
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $app->process(date('Y-m-d'));
        $this->assertTrue(file_exists('out.csv'), 'Did not create file');
        $actual = file_get_contents('out.csv');
        $this->assertEquals($this->expected(), $actual);
    }

    // dit is alweer unit-test-achtig.
    public function test_will_not_overwrite_existing_file()
    {
        $filesystem = $this->createMock(FileSystem::class);
        $filesystem->method('exists')->willReturn(true);
        $filesystem->expects($this->never())->method('write');
        $parameter = $this->createStub(Parameter::class);
        $formatter = $this->createStub(Formatter::class);
        $schedule = $this->createStub(PaySchedule::class);
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $status = $app->process(date('Y-m-d'));
        $this->assertEquals(1, $status, 'Should signal failure');
    }

    public function test_stops_if_not_writable()
    {
        $filesystem = $this->createMock(FileSystem::class);
        $filesystem->method('creatable')->willReturn(false);
        $filesystem->expects($this->never())->method('write');
        $parameter = $this->createStub(Parameter::class);
        $formatter = $this->createStub(Formatter::class);
        $schedule = $this->createStub(PaySchedule::class);
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $status = $app->process(date('Y-m-d'));
        $this->assertEquals(1, $status, 'Should signal failure');
    }

    public function test_stops_on_write_failure()
    {
        $filesystem = $this->createMock(FileSystem::class);
        $filesystem->method('creatable')->willReturn(true);
        $filesystem->expects($this->once())->method('write')->willReturn(false);
        $parameter = $this->createStub(Parameter::class);
        $formatter = $this->createStub(Formatter::class);
        $schedule = $this->createStub(PaySchedule::class);
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $status = $app->process(date('Y-m-d'));
        $this->assertEquals(1, $status, 'Should signal failure');
    }

    public function test_writes_schedule_to_default_file()
    {
        $filesystem = $this->createMock(FileSystem::class);
        $filesystem->method('creatable')->willReturn(true);
        $filesystem->expects($this->once())
            ->method('write')
            ->with('out.csv', '')
            ->willReturn(true);
        $parameter = $this->createStub(Parameter::class);
        $formatter = $this->createStub(Formatter::class);
        $schedule = $this->createStub(PaySchedule::class);
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $status = $app->process(date('Y-m-d'));
        $this->assertEquals(0, $status, 'Should not signal failure');
    }

    public function test_writes_schedule_to_specified_file()
    {
        $filesystem = $this->createMock(FileSystem::class);
        $filesystem->method('creatable')->willReturn(true);
        $filesystem->expects($this->once())
            ->method('write')
            ->with('special.csv', '')
            ->willReturn(true);
        $parameter = $this->createStub(Parameter::class);
        $parameter->method('get')->willReturn('special.csv');
        $formatter = $this->createStub(Formatter::class);
        $schedule = $this->createStub(PaySchedule::class);
        $app = new App($filesystem, $parameter, $formatter, $schedule);
        $status = $app->process(date('Y-m-d'));
        $this->assertEquals(0, $status, 'Should not signal failure');
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

