<?php

namespace App\Service;

use App\Entity\Town;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LongitudeOne\Spatial\Exception\InvalidValueException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class CsvService
{
    private mixed $fileToRead;

    /**
     * @throws Exception
     */
    public function __construct(
        private string $townFile,
        private EntityManagerInterface $entityManager,
        private PrepareTown $prepareTown = new PrepareTown()
    ) {
        $this->verifyFilename();
        //on ouvre le fichier en mode lecture
        $this->fileToRead = fopen($this->getFilename(), "r");
    }

    /**
     * @throws InvalidValueException
     */
    public function readTown(): void
    {
        // on vérifie la première ligne qui est celle des étiquettes
        $this->verifyFirstLineFile(fgets($this->fileToRead));

        while (!feof(($this->fileToRead))) { // tant qu'on est pas à la fin du fichier
            $line = fgets($this->fileToRead);

            // évite la levée d'une exception à la dernière ligne du fichier
            if (trim($line) === '') {
                break;
            }
            $arrayFromLine = explode(',', $line);
            $town = $this->verifyTownData($arrayFromLine);
            $this->entityManager->persist($town);
        }
        $this->entityManager->flush();

        fclose($this->fileToRead);
    }

    /**
     * @throws InvalidValueException
     * @throws Exception
     */
    public function verifyTownData(array $townArray): Town
    {
        $town = new Town();
        $point = new Point([$this->verifyLongitude($townArray[5]), $this->verifyLatitude($townArray[4])]);
        $town->setName($this->verifyTownName($townArray[1]))
            ->setZipCode($this->verifyZipCode($townArray[2]))
            ->setPoint($point);
        return $town;
    }

    /**
     * @throws Exception
     */
    public function verifyFirstLineFile(string $firstLine): void
    {
        $neededFirstLine = 'insee_code,city_code,zip_code,label,latitude,longitude,' .
            'department_name,department_number,region_name,region_geojson_name';
        if (trim($firstLine) !== $neededFirstLine) {
            fclose($this->fileToRead);
            throw new Exception('file doesn\'t match');
        }
    }

    /**
     * @throws Exception
     */
    public function verifyTownName(string $name): string
    {
        if (!preg_match('/[a-z\s]+/', $name)) {
            fclose($this->fileToRead);
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
            fclose($this->fileToRead);
            throw new Exception('it seems there\'s some problems with the town zipCode : ' . $zipCode);
        }
        return $zipCode;
    }

    /**
     * @throws Exception
     */
    public function verifyLatitude(string $latitude): float
    {
        $latitude = $this->prepareTown->preparePos($latitude);
        if ($latitude < -90 || $latitude > 90) {
            fclose($this->fileToRead);
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
            fclose($this->fileToRead);
            throw new Exception('it seems there\'s some problems with the longitude : ' . $longitude);
        }
        return $longitude;
    }

    /**
     * @throws Exception
     */
    public function verifyFilename(): void
    {
        if (!is_file($this->getFilename())) {
            fclose($this->fileToRead);
            throw new Exception('File not found.' . PHP_EOL .
                'Verify your sources directory or yours parameters in config.yalm');
        }
    }

    public function getFilename(): string
    {
        return dirname('.', 2) . $this->townFile;
    }
}
