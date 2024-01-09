<?php

namespace App\Service;

class PrepareTown
{
    public function prepareTownName(string $name): string
    {
        $name = strtoupper($name);
        if (str_starts_with($name, 'L ')) {
            echo 'plop';
            $name[1] = "'";
        }
        return str_replace([' L ', ' S ', ' D ', 'QU '], [' L\'', ' S\'', ' D\'', 'QU\''], $name);
    }

    public function preparePos(string $pos): float
    {
        return round(floatval($pos), 4);
    }

    public function prepareZipCode(string $zipCode): string
    {
        if (!preg_match('/\d{5}/', $zipCode)) {
            $zipCode = '0' . $zipCode;
        }
        return $zipCode;
    }
}
