<?php

namespace App;

require "vendor/autoload.php";

$filesystem = new ProductionFileSystem();
$parameter = new TerminalParameter($argv);
$formatter = new CsvFormatter();
$schedule = new PaySchedule();
$app = new App($filesystem, $parameter, $formatter, $schedule);
$status = $app->process(date('Y-m-d'));
if ($status) {
    echo implode(PHP_EOL, $app->messages());
}
exit($status);

