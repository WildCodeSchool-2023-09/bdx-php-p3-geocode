<?php

namespace App\Service;

use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

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
//    ELECTRA;891624884;help@electra.com;ELECTRA;help@electra.com;;ELECTRA;FRELCPGRAHC;;
//    Gradignan - Hôtel Campanile;Station dédiée à la recharge rapide;1 allée des demoiselles 33700 Gradignan;
//    33281;[-0.60280900,44.79029300];4;FRELCECTCH;;150;false;false;true;false;false;false;true;true;true;;
//    Accès libre;true;24/7;Accessibilité inconnue;Inconnu;false;Direct;N/A;2023-02-03;
//    Télécharger l'application ELECTRA pour réserver et payer sur go-electra.com;2023-08-02;;
//    2023-08-02T03:05:18.427000+00:00;623ca46c13130c3228abd018;e9bb3424-77cd-40ba-8bbd-5a19362d0365;
//    electra;-0.602809;44.790293;33700;Mérignac;False;True
}
