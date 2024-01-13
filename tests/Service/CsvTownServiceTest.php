<?php

namespace App\Tests\Service;

use App\Entity\Town;
use App\Service\CsvTownService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvTownServiceTest extends KernelTestCase
{
    public function testVerifyFilename(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $this->assertNull($csvService->verifyFilename());
    }

    public function testVerifyFirstLineFile(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $line1 = 'insee_code;city_code;zip_code;label;latitude;longitude;department_name;' .
            'department_number;region_name;region_geojson_name';
        $line2 = 'longitude;department_name;department_number;region_name;region_geojson_name';
        $this->assertNull($csvService->verifyFirstLineFile($line1));
        $this->expectException(\Exception::class);
        $csvService->verifyFirstLineFile($line2);
    }

    public function testVerifyTownName(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $name1 = 'bordeaux';
        $name2 = 'saint germain';
        $name3 = '';
        $name4 = '33000';
        $this->assertSame('BORDEAUX', $csvService->verifyTownName($name1));
        $this->assertSame('SAINT GERMAIN', $csvService->verifyTownName($name2));
        $this->expectException(\Exception::class);
        $csvService->verifyTownName($name3);
        $this->expectException(\Exception::class);
        $csvService->verifyTownName($name4);
    }

    public function testVerifyZipCode(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $zipCode1 = '33000';
        $zipCode2 = 'Bordeaux';
        $zipCode3 = '3300';
        $this->assertSame('33000', $csvService->verifyZipCode($zipCode1));
        $this->expectException(\Exception::class);
        $csvService->verifyZipCode($zipCode2);
        $this->assertSame('03300', $csvService->verifyZipCode($zipCode3));
    }

    public function testVerifyLatitude(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $lat1 = '45.248827847';
        $lat2 = '-45.248827847';
        $lat3 = '91.248827847';
        $lat4 = '-91.248827847';
        $this->assertSame(45.2488, $csvService->verifyLatitude($lat1));
        $this->assertSame(-45.2488, $csvService->verifyLatitude($lat2));
        $this->expectException(\Exception::class);
        $csvService->verifyLatitude($lat3);
        $this->expectException(\Exception::class);
        $csvService->verifyLatitude($lat4);
    }

    public function testVerifyLongitude(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTownService::class);
        $lon1 = '45.368827847';
        $lon2 = '-45.368827847';
        $lon3 = '191.258827847';
        $lon4 = '-191.258827847';
        $this->assertSame(45.3688, $csvService->verifyLongitude($lon1));
        $this->assertSame(-45.3688, $csvService->verifyLongitude($lon2));
        $this->expectException(\Exception::class);
        $csvService->verifyLongitude($lon3);
        $this->expectException(\Exception::class);
        $csvService->verifyLongitude($lon4);
    }
}
