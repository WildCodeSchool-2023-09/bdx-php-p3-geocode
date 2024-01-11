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
            fclose($this->file);
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
            fclose($this->file);
            throw new Exception('it seems there\'s some problems with the longitude : ' . $longitude);
        }
        return $longitude;
    }
}
