<?php

namespace App\Service\Route;

use ArgumentCountError;
use ValueError;

class Point
{
    private const RADIUS_OF_EARTH = 6371; // Earth Radius
    private float $latitude;


    private float $longitude;

    public function __construct(array $point)
    {
        if (!isset($point['lat']) || !isset($point['lng'])) {
            throw new ValueError('A point must have a latitude and a longitude');
        }
        if (count($point) !== 2) {
            throw new ArgumentCountError('Un point ne peut avoir qu\'une latitude et une longitude');
        }
        $this->latitude = $point['lat'];
        $this->longitude = $point['lng'];
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * This function use haversine formula. It determines the great-circle distance
     * between two points on a sphere given their longitudes and latitudes.
     * Results using the haversine formula may have an error of up to 0.5%
     * because the Earth is not a perfect sphere.
     *
     * @param Point $anotherPoint
     * @return float distance in kilometers
     */
    public function calcDistanceWith(Point $anotherPoint): float
    {
        // Address 1
        $latitudeOne = deg2rad($this->latitude);
        $longitudeOne = deg2rad($this->longitude);
        // Address 2
        $latitudeTwo = deg2rad($anotherPoint->getLatitude());
        $longitudeTwo = deg2rad($anotherPoint->getLongitude());

        $distanceLongitude = $longitudeTwo - $longitudeOne;
        $distanceLatitude = $latitudeTwo - $latitudeOne;

        // This is the Haversine Formula expressed Mathematically
        // a = sin²(Δφ/2) + cos φ1 ⋅ cos φ2 ⋅ sin²(Δλ/2)
        // c = 2 ⋅ atan2( √a, √(1−a) )
        // d = R ⋅ c

        // This is the haversine Formula expressed in PHP
        $aaa = sin($distanceLatitude / 2) * sin($distanceLatitude / 2) +
            cos($latitudeOne) * cos($latitudeTwo) * sin($distanceLongitude / 2) * sin($distanceLongitude / 2);
        $ccc = 2 * asin(sqrt($aaa));
        $distance = self::RADIUS_OF_EARTH * $ccc;

        return round($distance, 3);
    }
}
