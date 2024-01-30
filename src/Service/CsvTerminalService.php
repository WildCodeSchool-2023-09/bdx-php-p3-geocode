<?php

namespace App\Service;

use App\Entity\Terminal;
use App\Repository\TownRepository;
use App\Service\AbstractGeoCsvService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LongitudeOne\Spatial\Exception\InvalidValueException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use UnexpectedValueException;

class CsvTerminalService extends AbstractGeoCsvService
{
    public function __construct(
        protected string $sortingIndex1,
        protected string $sortingIndex2,
        protected string $filename,
        protected EntityManagerInterface $entityManager,
        //private TownRepository $townRepository,
        private PrepareTerminal $prepareTerminal,
        PrepareTown $prepareTown,
    ) {
        parent::__construct($sortingIndex1, $sortingIndex2, $filename, $entityManager, $prepareTown);
    }

//    /**
//     * @throws Exception
//     */
//    public function saveInDatabase(): void
//    {
//        $rows = $this->read();
//
//            usort($rows, fn($row1, $row2) => strcmp(
//                $row1[$this->sortingIndex1] . $row1[$this->sortingIndex2],
//                $row2[$this->sortingIndex1] . $row2[$this->sortingIndex2]
//            ));
//
//        $previousRow = null;
//        foreach ($rows as $row) {
//            if ($previousRow != $row) {
//                try {
//                    $object = $this->verifyData($row);
//                    $this->entityManager->persist($object);
//                    $previousRow = $row;
//                } catch (Exception $exception) {
//                    print_r($exception);
//                }
//            }
//        }
//        $this->entityManager->flush();
//    }

    public function getColumns(array $array): array
    {
        $result = [];
        $keys = [
            'adresse_station',
            'coordonneesXY',
            'nbre_pdc',
//            'puissance_nominale',
//            'prise_type_ef',
//            'prise_type_2',
//            'prise_type_combo_ccs',
//            'prise_type_chademo',
//            'prise_type_autre',
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
     * Receive a row from read() and return an object
     * @param array $data
     * @return Terminal
     * @throws InvalidValueException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function verifyData(array $data): Terminal
    {
        $terminal = new Terminal();
        $terminal->setPoint(new Point($this->verifyPos($data['coordonneesXY'])));
//        $addressData = $this->verifyAddressAndTown($data['adresse_station']);
        $terminal->setAddress($data['adresse_station']);
//        $terminal->setAddress($addressData['address']);
//        $terminal->setTown($this->townRepository->findOneByNameAndZipCode(
//            $addressData['town'],
//            $addressData['zip_code']
//        ));
        $terminal->setMaxPower(0);
        $terminal->setNumberOutlet($this->verifyPositiveNumber($data['nbre_pdc']));
//        $terminal->setOutletType($this->verifyOutletType($data));
        $terminal->setOutletType('inconnu');


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

    public function verifyPositiveNumber(int $number): int
    {
        if ($number < 0) {
            throw new UnexpectedValueException('It seems the value ' . $number . ' isn\'t positive');
        }
        return $number;
    }

    public function verifyOutletType(array $data): string
    {
        $outletType = $this->prepareTerminal->prepareOutletType($data);
        if (empty($outletType)) {
            $outletType = 'inconnu';
        }
        return $outletType;
    }
}
