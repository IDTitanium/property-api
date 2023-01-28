<?php

if (!function_exists('csvToArray')) {
    /**
       * Converts a csv to an array
       * @param object $csvFile
       * @return array
       */
    function csvToArray($csvFile)
    {
        $file = $csvFile->getRealPath();
        $csv = array_map('str_getcsv', file($file));

        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }
}
