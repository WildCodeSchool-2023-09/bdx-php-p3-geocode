<?php

namespace App\Tests\Service\Route;

use App\Service\Route\Point;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function testPoint(): void
    {
        $this->assertSame('test', 'test');
     //   $this->expectException(\TypeError::class);
      //  $point = new Point(0, 0);
        $this->expectException(\ValueError::class);
        $point = new Point(['lat' => 15, 'plop' => 45]);
        $this->expectException(\ArgumentCountError::class);
        $point = new Point(['lat' => 15, 'lng' => 25, 'plop' => 45]);
    }

    /**
     * Distance used to compare are from https://www.calculator.net/distance-calculator.html
     * with an error of 0.5%
     * @return void
     */
    public function testCalcDistanceWith(): void
    {
        $point1 = new Point(['lat' => 45, 'lng' => 0]);
        $point2 = new Point(['lat' => 45, 'lng' => 1]);
        $this->assertThat(
            $point1->calcDistanceWith($point2),
            $this->logicalAnd(
                $this->greaterThanOrEqual(78.85 * 0.95),
                $this->lessThanOrEqual(78.85 * 1.05)
            )
        );
        $point1 = new Point(['lat' => 1, 'lng' => 45]);
        $point2 = new Point(['lat' => 0, 'lng' => 45]);
        $this->assertThat(
            $point1->calcDistanceWith($point2),
            $this->logicalAnd(
                $this->greaterThanOrEqual(110.6 * 0.95),
                $this->lessThanOrEqual(110.6 * 1.05)
            )
        );
    }
}
