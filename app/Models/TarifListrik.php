<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifListrik extends Model
{
    protected $table = 'tarif_listrik';

    protected $fillable = [
        'daya_va',
        'tarif_per_kwh',
    ];
}
