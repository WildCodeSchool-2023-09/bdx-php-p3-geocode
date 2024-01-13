<?php

namespace App\Service;

use App\Entity\Town;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @throws Exception
     */
    public function verifyTownName(string $name): string
    {
        if (!preg_match('/[a-z\s]+/', $name)) {
            fclose($this->file);
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
            fclose($this->file);
            throw new Exception('it seems there\'s some problems with the town zipCode : ' . $zipCode);
        }
        return $zipCode;
    }
}
