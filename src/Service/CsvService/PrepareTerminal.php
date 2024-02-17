<?php

namespace App\Service\CsvService;

use Exception;

class PrepareTerminal
{
    public function preparePos(string $string): array
    {
        [$longitude, $latitude] = explode(',', $string);
        $longitude = round(floatval(substr($longitude, 1)), 5) ;
        $latitude = round(floatval(substr($latitude, 0, -1)), 5);
        return ['longitude' => $longitude,
                'latitude' => $latitude];
    }

    public function prepareAddressAndTown(string $string): array
    {
        $result = [];
        // 1 allée des demoiselles 33700 Gradignan
        $zipCode = $this->getZipCode($string);

        //[$address, $town] = explode(' ' . $zipCode . ' ', $string);
        $array = explode(' ' . $zipCode . ' ', $string);
        if (count($array) === 1) {
            $town = $array[0];
            $address = $array[0];
        } else {
            [$address, $town] = $array;
        }
        $town = $this->prepareTown($town);
        $result['address'] = $address;
        $result['zip_code'] = $zipCode;
        $result['town'] = $town;
        return $result;
    }

    public function getZipCode(string $string): string
    {
        $pattern = '/\d{5}/';
        preg_match_all($pattern, $string, $zipArray);
        if (count($zipArray[0]) === 0) {
            throw new Exception('address "' . $string . '" does not match');
        }
        return $zipArray[0][count($zipArray[0]) - 1];
    }

    public function prepareTown(string $string): string
    {
        $town = str_replace('-', ' ', $this->withdrawAccents($string));
        return strtoupper($town);
    }

    public function withdrawAccents(string $string): string
    {
            $search  = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô',
                'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í',
                'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ'];

            $replace = ['A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O',
                'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i',
                'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y'];

            return str_replace($search, $replace, $string);
    }

    public function prepareNumber(string $string): int
    {
        $number = intval($string);

        return $number;
    }
//    ELECTRA;891624884;help@electra.com;ELECTRA;help@electra.com;;ELECTRA;FRELCPGRAHC;;
//    Gradignan - Hôtel Campanile;Station dédiée à la recharge rapide;1 allée des demoiselles 33700 Gradignan;
//    33281;[-0.60280900,44.79029300];4;FRELCECTCH;;150;false;false;true;false;false;false;true;true;true;;
//    Accès libre;true;24/7;Accessibilité inconnue;Inconnu;false;Direct;N/A;2023-02-03;
//    Télécharger l'application ELECTRA pour réserver et payer sur go-electra.com;2023-08-02;;
//    2023-08-02T03:05:18.427000+00:00;623ca46c13130c3228abd018;e9bb3424-77cd-40ba-8bbd-5a19362d0365;
//    electra;-0.602809;44.790293;33700;Mérignac;False;True
    public function prepareOutletType(array $data): string
    {
        $outletType = '';
        foreach ($data as $key => $column) {
            if ($column === 'true') {
                $outletType .= $key . ' ';
            }
        }
        return trim($outletType);
    }
}
