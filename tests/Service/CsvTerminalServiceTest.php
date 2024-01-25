<?php

namespace App\Tests\Service;

use App\Entity\Town;
use App\Service\CsvTerminalService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CsvTerminalServiceTest extends KernelTestCase
{
    public function testVerifyFilename(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTerminalService::class);
        $this->assertNull($csvService->verifyFilename());
    }

    public function testVerifyFirstLineFile(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTerminalService::class);
        $line1 = 'nom_amenageur;siren_amenageur;contact_amenageur;nom_operateur;contact_operateur;' . //4
            'telephone_operateur;nom_enseigne;id_station_itinerance;id_station_local;nom_station;' . //9
            'implantation_station;adresse_station;code_insee_commune;coordonneesXY;nbre_pdc;' . //14
            'id_pdc_itinerance;id_pdc_local;puissance_nominale;prise_type_ef;prise_type_2;' . //19
            'prise_type_combo_ccs;prise_type_chademo;prise_type_autre;gratuit;paiement_acte;paiement_cb;' . //25
            'paiement_autre;tarification;condition_acces;reservation;horaires;accessibilite_pmr;' . //31
            'restriction_gabarit;station_deux_roues;raccordement;num_pdl;date_mise_en_service;observations;' .
            'date_maj;cable_t2_attache;last_modified;datagouv_dataset_id;datagouv_resource_id;' .
            'datagouv_organization_or_owner;consolidated_longitude;consolidated_latitude;consolidated_code_postal;' .
            'consolidated_commune;consolidated_is_lon_lat_correct;consolidated_is_code_insee_verified';
        $line2 = 'longitude;department_name;department_number;region_name;region_geojson_name';
        $this->assertNull($csvService->verifyFirstLineFile($line1));
        $this->expectException(\Exception::class);
        $csvService->verifyFirstLineFile($line2);
    }

    public function testVerifyPos(): void
    {
        self::bootKernel();
        $container = static ::getContainer();
        $csvService = $container->get(CsvTerminalService::class);
        $this->assertSame([5.363 , 43.289], $csvService->verifyPos('[5.362979 , 43.288972]'));
        $this->assertSame([-5.363 , 43.289], $csvService->verifyPos('[-5.362979 , 43.288972]'));
    }
}
