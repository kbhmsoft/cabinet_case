<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class GovCaseImport implements ToArray
{
    public function array(array $array)
    {
        // Returning the Excel data as an array for processing
        return $array;
    }
}
