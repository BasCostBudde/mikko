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
        $month_date = strtotime(date('Y') .'-'. $month .'-15');
        $month_name = date('F', $month_date);
        $paydate = strtotime('last day of this month', $month_date);
        $bonuspaydate = $month_date;
        if (in_array(date('w', $bonuspaydate), [0, 6])) {
            $bonuspaydate = strtotime('next wednesday', $bonuspaydate);
        }
        return [$month_name, date('Y-m-d', $paydate), date('Y-m-d', $bonuspaydate)];
    }, $remaining_months
);
$fh = fopen($filename, 'w');
foreach ($out as $line) {
    fputcsv($fh, $line);
}
fclose($fh);
echo "$filename written.\n";
