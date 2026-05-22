<?php

namespace App;

class PaySchedule
{

    private int $date;

    /**
     * @return array<array{string,string,string}>
     */
    public function get(string $date): array
    {
        $this->date = strtotime($date);
        return array_map(
            [$this, 'dates_in'],
            $this->remaining_months()
        );
    }

    /**
     * @return array<int>
     */
    private function remaining_months(): array
    {
        return range(date('m', ($this->date)), 12);
    }

    /**
     * @return array{string,string,string}
     */
    private function dates_in(int $month): array
    {
        return [
            $this->monthName($month),
            $this->formatted($this->getPaydate($month)),
            $this->formatted($this->getBonuspaydate($month))
        ];
    }

    private function monthName(int $month): string
    {
        return date('F', $this->startdate($month));
    }

    private function startdate(int $month): int
    {
        return strtotime(date('Y', $this->date)."-$month-01");
    }

    private function getPaydate(int $month): int
    {
        $paydate = $this->paydate($month);
        if ($this->inWeekend($paydate)) {
            $paydate = $this->alternate_paydate($paydate);
        }
        return $paydate;
    }

    private function getBonuspaydate(int $month): int
    {
        $bonuspaydate = $this->bonusdate($month);
        if ($this->inWeekend($bonuspaydate)) {
            $bonuspaydate = $this->alternate_bonuspaydate($bonuspaydate);
        }
        return $bonuspaydate;
    }

    private function paydate(int $month): int
    {
        return strtotime('last day of this month', $this->startdate($month));
    }

    private function alternate_paydate(int $paydate): int
    {
        return strtotime('last friday', $paydate);
    }

    private function bonusdate(int $month): int
    {
        return strtotime('+14 day', $this->startdate($month));
    }

    private function alternate_bonuspaydate(int $bonuspaydate): int
    {
        return strtotime('next wednesday', $bonuspaydate);
    }

    private function inWeekend(int $date): bool
    {
        return (in_array(date('w', $date), [0, 6])); 
    }

    private function formatted(int $date): string
    {
        return date('Y-m-d', $date); 
    }

}
