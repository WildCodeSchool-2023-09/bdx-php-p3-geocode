<?php

namespace App\Service\CsvService;

use App\Entity\Town;
use Exception;
use LongitudeOne\Spatial\Exception\InvalidValueException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class CsvTownService extends AbstractGeoCsvService
{
    protected function getColumns(array $array): array
    {
        $result = [];
        $keys = [
            'city_code',
            'zip_code',
            'latitude',
            'longitude'
        ];

        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }

        return $result;
    }

    /**
     * @throws InvalidValueException
     * @throws Exception
     */
    public function verifyData(array $data): Town
    {
        $town = new Town();
        $point = new Point([$this->verifyLongitude($data['longitude']), $this->verifyLatitude($data['latitude'])]);
        $town->setName($this->verifyTownName($data['city_code']))
            ->setZipCode($this->verifyZipCode($data['zip_code']))
            ->setPoint($point);
        return $town;
    }

    /**
     * @throws Exception
     */
    public function verifyFirstLineFile(string $firstLine): void
    {
        $neededFirstLine = 'insee_code;city_code;zip_code;label;latitude;longitude;' .
            'department_name;department_number;region_name;region_geojson_name';
        if (trim($firstLine) !== $neededFirstLine) {
            fclose($this->file);
            throw new Exception('file doesn\'t match');
        }
    }
}
