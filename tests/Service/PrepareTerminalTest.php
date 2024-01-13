<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\PrepareTerminal;

class PrepareTerminalTest extends TestCase
{
    public function testPreparePos(): void
    {
        $prepareTerminal = new PrepareTerminal();
        $this->assertEquals(['longitude' => -0.60281,
                            'latitude' => 44.79029], $prepareTerminal->preparePos('[-0.60280900,44.79029300]'));
    }
}
