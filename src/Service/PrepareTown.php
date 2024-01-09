<?php

namespace App\Service;

class PrepareTown
{
    public function prepareTownName(string $name): string
    {
        $string = strtoupper($name);
        return str_replace([' L ', ' S ', ' D '], [' L\'', ' S\'', ' D\''], $string);
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
