<?php

$filename = 'out.csv';
if ($argc > 1) {
    $filename = $argv[1];
}
if (file_exists($filename)) {
    echo "$filename already exists.\n";
    exit(1);
}
if (!touch($filename)) {
    echo "$filename cannot be created.\n";
    exit(1);
}
$remaining_months = range(date('m'), 12);
$out = array_map(
    function ($month) {
        $bonuspaydate = strtotime(date('Y') .'-'. $month .'-15');
        $monthname = date('F', $bonuspaydate);
        $paydate = strtotime('last day of this month', $bonuspaydate);
        if (in_array(date('w', $paydate), [0, 6])) {
            $paydate = strtotime('last friday', $paydate);
        }
        if (in_array(date('w', $bonuspaydate), [0, 6])) {
            $bonuspaydate = strtotime('next wednesday', $bonuspaydate);
        }
        return [$monthname, date('Y-m-d', $paydate), date('Y-m-d', $bonuspaydate)];
    }, $remaining_months
);
$fh = fopen($filename, 'w');
foreach ($out as $line) {
    fputcsv($fh, $line);
}
fclose($fh);
echo "$filename written.\n";
