<?php

namespace App\Service;

use App\Service\AbstractCsvService;
use Exception;

abstract class AbstractGeoCsvService extends AbstractCsvService
{
    /**
     * @throws Exception
     */
    public function verifyLatitude(string $latitude): float
    {
        $latitude = $this->prepareTown->preparePos($latitude);
        if ($latitude < -90 || $latitude > 90) {
            throw new Exception('it seems there\'s some problems with the latitude : ' . $latitude);
        }
        return $latitude;
    }

    /**
     * @throws Exception
     */
    public function verifyLongitude(string $longitude): float
    {
        $longitude = $this->prepareTown->preparePos($longitude);
        if ($longitude < -180 || $longitude > 180) {
            throw new Exception('it seems there\'s some problems with the longitude : ' . $longitude);
        }
        return $longitude;
    }

    /**
     * @throws Exception
     */
    public function verifyTownName(string $name): string
    {
        if (!preg_match('/[a-zA-Z\s]+/', $name)) {
            throw new Exception('it seems there\'s some problems with the town name : ' . $name);
        }
        return $this->prepareTown->prepareTownName($name);
    }

    /**
     * @throws Exception
     */
    public function verifyZipCode(string $zipCode): string
    {
        $zipCode = $this->prepareTown->prepareZipCode($zipCode);
        if (!preg_match('/\d{5}/', $zipCode)) {
            throw new Exception('it seems there\'s some problems with the town zipCode : ' . $zipCode);
        }
        return $zipCode;
    }
}
