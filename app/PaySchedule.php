<?php

namespace App;

class PaySchedule
{

    private int $date;

    public function get(string $date): array
    {
        $this->date = strtotime($date);
        return array_map(
            [$this, 'dates_in'],
            $this->remaining_months()
        );
    }

    private function remaining_months()
    {
        return range(date('m', ($this->date)), 12);
    }

    private function dates_in($month)
    {
        return [
            $this->monthName($month),
            $this->formatted($this->getPaydate($month)),
            $this->formatted($this->getBonuspaydate($month))
        ];
    }

    private function monthName($month)
    {
        return date('F', $this->startdate($month));
    }

    private function startdate($month)
    {
        return strtotime(date('Y', $this->date)."-$month-01");
    }

    private function getPaydate($month)
    {
        $paydate = $this->paydate($month);
        if ($this->inWeekend($paydate)) {
            $paydate = $this->alternate_paydate($paydate);
        }
        return $paydate;
    }

    private function getBonuspaydate($month)
    {
        $bonuspaydate = $this->bonusdate($month);
        if ($this->inWeekend($bonuspaydate)) {
            $bonuspaydate = $this->alternate_bonuspaydate($bonuspaydate);
        }
        return $bonuspaydate;
    }

    private function paydate($month)
    {
        return strtotime('last day of this month', $this->startdate($month));
    }

    private function alternate_paydate($paydate)
    {
        return strtotime('last friday', $paydate);
    }

    private function bonusdate($month)
    {
        return strtotime('+14 day', $this->startdate($month));
    }

    private function alternate_bonuspaydate($bonuspaydate)
    {
        return strtotime('next wednesday', $bonuspaydate);
    }

    private function inWeekend($date)
    {
        return (in_array(date('w', $date), [0, 6])); 
    }

    private function formatted($date)
    {
        return date('Y-m-d', $date); 
    }

}
