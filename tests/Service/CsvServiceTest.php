<?php

namespace App\Tests\Service;

use App\Service\CsvBasiqueService;
use App\Service\CsvService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvServiceTest extends KernelTestCase
{
    public function testVerifyFirstLineFile(): void
    {
        $csvService = new CsvBasiqueService();
        $line1 = 'insee_code,city_code,zip_code,label,latitude,longitude,department_name,
        department_number,region_name,region_geojson_name';
        $line2 = 'longitude,department_name,department_number,region_name,region_geojson_name';
        $this->assertTrue($csvService->verifyFirstLineFile($line1));
        $this->assertFalse($csvService->verifyFirstLineFile($line2));
    }

    public function testVerifyFirstLineFileWithRealClass(): void
    {
        self::bootKernel();
        $contenair = static ::getContainer();
        $csvService = $contenair->get(CsvService::class);
        $line1 = 'insee_code,city_code,zip_code,label,latitude,longitude,department_name,department_number,
                region_name,region_geojson_name';
        $line2 = 'longitude,department_name,department_number,region_name,region_geojson_name';
        $this->assertTrue($csvService->verifyFirstLineFile($line1));
        $this->assertFalse($csvService->verifyFirstLineFile($line2));
    }

    public function testVerifyZipCode(): void
    {
        self::bootKernel();
        $contenair = static ::getContainer();
        $csvService = $contenair->get(CsvService::class);
        $zipCode1 = '33000';
        $zipCode2 = 'Bordeaux';
        $zipCode3 = '3300';
        $this->assertTrue($csvService->verifyZipCode($zipCode1));
        $this->assertFalse($csvService->verifyZipCode($zipCode2));
        $this->assertFalse($csvService->verifyZipCode($zipCode3));
    }

    public function testVerifyTownName(): void
    {
        self::bootKernel();
        $contenair = static ::getContainer();
        $csvService = $contenair->get(CsvService::class);
        $name1 = 'bordeaux';
        $name2 = 'saint germain';
        $name3 = '';
        $name4 = '33000';
        $this->assertTrue($csvService->verifyTownName($name1));
        $this->assertTrue($csvService->verifyTownName($name2));
        $this->assertFalse($csvService->verifyTownName($name3));
        $this->assertFalse($csvService->verifyTownName($name4));
    }
}
