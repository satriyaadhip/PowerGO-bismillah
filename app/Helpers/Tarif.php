<?php

namespace App\Helpers;

class Tarif
{
    public static function getTarifPerKwh(int $daya)
    {
        return match ($daya) {
            450  => 415,
            900  => 1352,
            1300 => 1444.7,
            2200 => 1444.7,
            default => 1500,
        };
    }
}
