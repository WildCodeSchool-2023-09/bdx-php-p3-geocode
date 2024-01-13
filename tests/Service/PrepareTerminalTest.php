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

    public function testGetZipCode(): void
    {
        $prepareTerminal = new PrepareTerminal();
        $this->assertSame(
            '33700',
            $prepareTerminal->getZipCode('1 allée des demoiselles 33700 Gradignan')
        );
        $this->assertSame(
            '93300',
            $prepareTerminal->getZipCode('10200 rue du Port 93300 Aubervilliers')
        );
    }

    public function testPrepareAddressAndTown(): void
    {
        $prepareTerminal = new PrepareTerminal();
        $string1 = '1 allée des demoiselles 33700 Gradignan';
        $string2 = '8 rue de Champfleur 49124 Saint-Barthélemy-d\'Anjou';
        $address1 = [
            'address' => '1 allée des demoiselles',
            'zip_code' => '33700',
            'town' => 'GRADIGNAN'
        ];
        $address2 = [
            'address' => '8 rue de Champfleur',
            'zip_code' => '49124',
            'town' => 'SAINT BARTHELEMY D\'ANJOU'
        ];
        $this->assertEquals($address1, $prepareTerminal->prepareAddressAndTown($string1));
        $this->assertEquals($address2, $prepareTerminal->prepareAddressAndTown($string2));
    }
}
