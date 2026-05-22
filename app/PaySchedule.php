<?php

namespace App;

class PaySchedule
{

    public function get(string $date): array
    {
        $remaining_months = range(date('m', strtotime($date)), 12);
        return array_map(
            function ($month) use ($date) {
                $bonuspaydate = strtotime(date('Y', strtotime($date)) .'-'. $month .'-15');
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
    }

}
