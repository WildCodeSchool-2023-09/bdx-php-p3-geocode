<?php

namespace App\Service;

use App\Entity\Terminal;
use App\Repository\TownRepository;
use App\Service\AbstractGeoCsvService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LongitudeOne\Spatial\Exception\InvalidValueException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class CsvTerminalService extends AbstractGeoCsvService
{
    public function __construct(
        string $filename,
        EntityManagerInterface $entityManager,
        private TownRepository $townRepository,
        private PrepareTerminal $prepareTerminal = new PrepareTerminal(),
        PrepareTown $prepareTown = new PrepareTown(),
    ) {
        parent::__construct($filename, $entityManager, $prepareTown);
    }

    public function getColumns(array $array): array
    {
        $result = [];
        $keys = [
            'adresse_station',
            'coordonneesXY',
            'puissance_nominale',
            'prise_type_ef',
            'prise_type_2',
            'prise_type_combo_ccs',
            'prise_type_chademo',
            'prise_type_autre',
            'horaires',
        ];

        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }

        return $result;
    }
    /**
     * @throws Exception
     */
    public function verifyFirstLineFile(string $firstLine): void
    {
        $neededFirstLine = 'nom_amenageur;siren_amenageur;contact_amenageur;nom_operateur;contact_operateur;' . //4
            'telephone_operateur;nom_enseigne;id_station_itinerance;id_station_local;nom_station;' . //9
            'implantation_station;adresse_station;code_insee_commune;coordonneesXY;nbre_pdc;' . //14
            'id_pdc_itinerance;id_pdc_local;puissance_nominale;prise_type_ef;prise_type_2;' . //19
            'prise_type_combo_ccs;prise_type_chademo;prise_type_autre;gratuit;paiement_acte;paiement_cb;' . //25
            'paiement_autre;tarification;condition_acces;reservation;horaires;accessibilite_pmr;' . //31
            'restriction_gabarit;station_deux_roues;raccordement;num_pdl;date_mise_en_service;observations;' .
            'date_maj;cable_t2_attache;last_modified;datagouv_dataset_id;datagouv_resource_id;' .
            'datagouv_organization_or_owner;consolidated_longitude;consolidated_latitude;consolidated_code_postal;' .
            'consolidated_commune;consolidated_is_lon_lat_correct;consolidated_is_code_insee_verified';
        if (trim($firstLine) !== $neededFirstLine) {
            fclose($this->file);
            throw new Exception('file doesn\'t match');
        }
    }

    // point -> 13 => [2.37351000,48.91973300] // ok
    // address -> 11 => 102 rue du Port 93300 Aubervilliers
    // outletType -> ef 2 combo-ccs chademo autre [18:22]
    // numberOutlet -> 14
    // maxPower -> 17
    // town -> extract zipcode & town, findByZipCodeAndName()
    // opened -> 30
    /**
     * @throws InvalidValueException
     * @throws Exception
     */
    public function verifyData(array $data): Terminal
    {
        $terminal = new Terminal();
        $terminal->setPoint(new Point($this->verifyPos($data['coordonneesXY'])));
        $addressData = $this->verifyAddressAndTown($data['address']);
        $terminal->setAddress($addressData['address']);
        $terminal->setTown($this->townRepository->findOneByNameAndZipCode($data['town'], $addressData['zip_code']));

        return $terminal;
    }

    /**
     * @throws Exception
     */
    public function verifyPos(string $coordinates): array
    {
        $coords = $this->prepareTerminal->preparePos($coordinates);
        return [$this->verifyLongitude($coords['longitude']),
            $this->verifyLatitude($coords['latitude'])];
    }

    /**
     * @throws Exception
     */
    public function verifyAddressAndTown(string $addressPrepared): array
    {
        $data = $this->prepareTerminal->prepareAddressAndTown($addressPrepared);
        $data['town'] = $this->verifyTownName($data['town']);
        $data['zip_code'] = $this->verifyZipCode($data['zip_code']);
        return $data;
    }
}
