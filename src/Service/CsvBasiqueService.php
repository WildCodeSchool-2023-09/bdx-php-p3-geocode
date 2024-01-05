<?php

namespace App\Service;

class CsvBasiqueService
{
    public function verifyFirstLineFile(string $firstLine): bool
    {
        $firstLineArray = array_map('trim', explode(',', $firstLine));
        $neededFirstLine = ['insee_code',
                            'city_code',
                            'zip_code',
                            'label',
                            'latitude',
                            'longitude',
                            'department_name',
                            'department_number',
                            'region_name',
                            'region_geojson_name'];
        return $firstLineArray === $neededFirstLine;
    }
}
