<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\Device;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'voltage',
        'amperage',
        'watt',
        'timestamp',
    ];

    protected $dates = [
        'timestamp',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

}
