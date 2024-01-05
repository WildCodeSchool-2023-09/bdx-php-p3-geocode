<?php

namespace App\Tests\Service;

use App\Service\CsvBasiqueService;
use App\Service\CsvService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvBasiqueServiceTest extends KernelTestCase
{
    public function testVerifyFirstLineFile(): void
    {
//        self::bootKernel();
//        $contenair = static ::getContainer();
//        $csvService = $contenair->get(CsvService::class);
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
}
