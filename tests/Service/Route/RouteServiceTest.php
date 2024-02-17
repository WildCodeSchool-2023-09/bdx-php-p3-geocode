<?php

namespace App\Tests\Service\Route;

use App\Service\Route\Point;
use App\Service\Route\RouteService;
use PHPUnit\Framework\TestCase;

class RouteServiceTest extends TestCase
{
    public function testGetAllPoints(): void
    {
        $routeService = new RouteService();
        $this->assertEquals(
            [
                ['lat' => 48.40004, 'lng' => -4.50247],
                ['lat' => 48.40001, 'lng' => -4.50313],
                ['lat' => 48.4, 'lng' => -4.50343],
            ],
            $routeService->getAllPoints('[
                {"lat":48.40004,"lng":-4.50247},
                {"lat":48.40001,"lng":-4.50313},
                {"lat":48.4,"lng":-4.50343}]')
        );
    }

    public function testFindClosest(): void
    {
        $route = new RouteService();
        $start = ['lat' => 45, 'lng' => 0];
        $haystack = [
            ['lat' => 45, 'lng' => 1],
            ['lat' => 45, 'lng' => 1.25],
            ['lat' => 45, 'lng' => 1.5]
        ];
        $this->assertEquals(['lat' => 45, 'lng' => 1.25], $route->findClosest($start, 100, $haystack));
    }

    public function testFindNextStep(): void
    {
        $route = new RouteService();
        $start = ['lat' => 45, 'lng' => 0];
        $points = [
            ['lat' => 45, 'lng' => 0],
            ['lat' => 45, 'lng' => 0.25],
            ['lat' => 45, 'lng' => 0.5],
            ['lat' => 45, 'lng' => 0.75],
            ['lat' => 45, 'lng' => 1],
            ['lat' => 45, 'lng' => 1.25],
            ['lat' => 45, 'lng' => 1.5],
            ['lat' => 45, 'lng' => 1.75],
            ['lat' => 45, 'lng' => 2],
            ['lat' => 45, 'lng' => 2.25],
            ['lat' => 45, 'lng' => 2.5],
            ['lat' => 45, 'lng' => 2.75],
            ['lat' => 45, 'lng' => 3],
        ];
        $this->assertEquals(
            ['lat' => 45, 'lng' => 1.25],
            $route->findNextStep($start, $points)
        );
        $this->assertEquals(
            ['lat' => 45, 'lng' => 2.50],
            $route->findNextStep($start, $points, 200)
        );
    }
}
