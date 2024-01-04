<?php

namespace App\Service;

use App\Entity\Town;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CsvService
{
    public function __construct(private EntityManagerInterface $entityManager, private string $townFile)
    {
    }

    public function readTown(): void
    {
        $filename = dirname('.', 2) . $this->townFile;
        if (!is_file($filename)) {
            throw new Exception('File not find.' . PHP_EOL .
                'Verify your sources directory or yours parameters in config.yalm');
        }
        $fileToRead = fopen($filename, "r");

        if (!$this->verifyFile(fgets($fileToRead))) {
            fclose($fileToRead);
            throw new Exception('file doesn\'t match');
        } // on passe la première ligne qui est celle des étiquettes


        while (!feof(($fileToRead))) { // tant qu'on est pas à la fin du fichier
            $line = fgets($fileToRead);
            $arrayFromLine = explode(',', $line);
            $town = new Town();
            if (!$this->verifyTownData($arrayFromLine)) {
                throw new Exception('it seems there\'s some problems with towns data!');
            }
            $town->setName($arrayFromLine[1])
                ->setPostalCode(intval($arrayFromLine[2]))
                ->setLatitude(floatval($arrayFromLine[4]))
                ->setLongitude(floatval($arrayFromLine[5]));
            $this->entityManager->persist($town);
        }
        $this->entityManager->flush();

        fclose($fileToRead);
    }

    private function verifyTownData(array $townArray): bool
    {
        return $this->verifyTownName($townArray[1]) &&
                $this->verifyZipCode($townArray[2]);
    }
    private function verifyFile(string $firstLine): bool
    {
         return $firstLine === 'insee_code,city_code,zip_code,label,latitude,
         longitude,department_name,department_number,region_name,region_geojson_name';
    }

    private function verifyTownName(string $name): bool
    {
        if (empty(trim($name))) {
            return false;
        }
        return true;
    }

    private function verifyZipCode(string $zipCode): bool
    {
        return preg_match('/[0-9]{5}/', $zipCode);
    }
}
