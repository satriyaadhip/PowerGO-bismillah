<?php

namespace App\Services;

use App\Models\Record;
use Carbon\Carbon;

class EnergyService
{
    protected int $defaultInitialKwh = 40; // bisa kamu ubah kapan saja

    public function getLatestRecord(): ?Record
    {
        return Record::orderBy('timestamp', 'desc')->first();
    }

    public function avgWattLast24Hours(): float
    {
        return (float) Record::where('timestamp', '>=', now()->subDay())->avg('watt') ?? 0;
    }

    public function avgWhLast24Hours(): float
    {
        return round($this->avgWattLast24Hours(), 2);
    }

    public function hourlyDataLast24Hours(): array
    {
        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->get();

        if ($records->isEmpty()) return [];

        return $records->groupBy(function ($r) {
            return Carbon::parse($r->timestamp)->format('H:00');
        })->map(function ($group, $hour) {
            $avgWatt = round($group->avg('watt'), 2);
            $kwh = round($avgWatt / 1000, 3);
            $cost = round($kwh * 13750, 0);

            return [
                'time' => $hour,
                'watt' => $avgWatt,
                'kwh' => $kwh,
                'cost' => $cost,
            ];
        })->values()->toArray();
    }

    public function totalKwhLast24Hours(): float
    {
        $avgWatt = $this->avgWattLast24Hours();
        return round(($avgWatt / 1000) * 24, 3);
    }

    public function remainingKwh(float $initialKwh = null): float
    {
        $initial = $initialKwh ?? $this->defaultInitialKwh;
        $used = $this->totalKwhLast24Hours();
        return max(round($initial - $used, 2), 0);
    }
}