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

    public function testPrepareNumber(): void
    {
        $prepareTerminal = new PrepareTerminal();
        $this->assertSame(1, $prepareTerminal->prepareNumber('1'));
        $this->assertSame(350, $prepareTerminal->prepareNumber('350'));
    }

    public function testPrepareOutletType(): void
    {
        //;26250;;28;FRELCEHCQC;;300;false;false;true;false;false;false;true;true;true;;Accès libre;true;24/7;Accessibilité inconnue;Inconnu;false;Direct;N/A;2023-06-21;Télécharger l'application ELECTRA pour réserver et payer sur go-electra.com;2023-08-02;;2023-08-02T03:05:18.427000+00:00;623ca46c13130c3228abd018;e9bb3424-77cd-40ba-8bbd-5a19362d0365;electra;4.876109;45.019158;26600;Pont-de-l'Isère;True;True
        $prepareTerminal = new PrepareTerminal();
        $data1 = [
            'adresse_station' => 'A7 - Aire Latitude 45 26600 Pont-de-l\'Isère',
            'coordonneesXY' => '[4.87610900,45.01915800]',
            'nbre_pdc' => '28',
            'puissance_nominale' => '300',
            'prise_type_ef' => 'false',
            'prise_type_2' => 'false',
            'prise_type_combo_ccs' => 'true',
            'prise_type_chademo' => 'false',
            'prise_type_autre' => 'false',
            'horaires' => '24/7',
        ];
        $this->assertSame('prise_type_combo_ccs', $prepareTerminal->prepareOutletType($data1));
        $data2 = [
            'adresse_station' => 'A7 - Aire Latitude 45 26600 Pont-de-l\'Isère',
            'coordonneesXY' => '[4.87610900,45.01915800]',
            'nbre_pdc' => '28',
            'puissance_nominale' => '300',
            'prise_type_ef' => 'false',
            'prise_type_2' => 'false',
            'prise_type_combo_ccs' => 'false',
            'prise_type_chademo' => 'false',
            'prise_type_autre' => 'false',
            'horaires' => '24/7',
        ];
        $this->assertSame('', $prepareTerminal->prepareOutletType($data2));
        $data3 = [
            'adresse_station' => 'A7 - Aire Latitude 45 26600 Pont-de-l\'Isère',
            'coordonneesXY' => '[4.87610900,45.01915800]',
            'nbre_pdc' => '28',
            'puissance_nominale' => '300',
            'prise_type_ef' => 'false',
            'prise_type_2' => 'true',
            'prise_type_combo_ccs' => 'false',
            'prise_type_chademo' => 'false',
            'prise_type_autre' => 'true',
            'horaires' => '24/7',
        ];
        $this->assertSame('prise_type_2 prise_type_autre', $prepareTerminal->prepareOutletType($data3));
    }
}
