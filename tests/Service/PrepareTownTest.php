<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\PrepareTown;

class PrepareTownTest extends TestCase
{
    public function testPrepareTownName(): void
    {
        $prepareTown = new PrepareTown();
        $this->assertSame('VILLIERS L\'ALLAIN', $prepareTown->prepareTownName('villiers l allain'));
        $this->assertSame('ALBON D\'ARDECHE', $prepareTown->prepareTownName('albon d ardeche'));
        $this->assertSame('L\'AIGUILLON LA PRESQU\'ILE', $prepareTown->prepareTownName('l aiguillon la presqu ile'));
    }

    public function testPreparePos(): void
    {
        $prepareTown = new PrepareTown();
        $this->assertSame(49.2488, $prepareTown->preparePos('49.248827847'));
        $this->assertSame(-49.2488, $prepareTown->preparePos('-49.248827847'));
    }
}
