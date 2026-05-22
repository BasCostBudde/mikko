<?php

namespace App;

class CsvFormatter implements Formatter
{

    public function format(array $input): string
    {
        return $this->str_putcsv($input);
    }

    private function str_putcsv($data)
    {
        $fh = fopen('php://temp', 'rw');
        foreach ($data as $row) {
            fputcsv($fh, $row);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
        return $csv;
    }

}
