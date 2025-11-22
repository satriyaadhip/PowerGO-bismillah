<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Carbon\Carbon;

class GraphController extends Controller
{
    public function totalDaya()
    {
        // Ambil data record 24 jam terakhir dari MySQL
        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->get();

        if ($records->isEmpty()) {
            return view('dashboard.total_daya')->with([
                'hourlyData' => [],
                'hourlyChartLabels' => [],
                'hourlyChartData' => [],
                'totalKwh' => 0,
                'totalCost' => 0,
                'weeklyData' => [],
                'weeklyChartLabels' => [],
                'weeklyChartKwh' => [],
                'weeklyChartCost' => [],
                'weeklyTotalKwh' => 0,
                'weeklyTotalCost' => 0,
                'weeklyAvgKwh' => 0,
                'weeklyAvgWatt' => 0
            ]);
        }

        // Format data per jam
        $hourlyFormatted = $records->groupBy(function ($record) {
            return Carbon::parse($record->timestamp)->format('H:00 - H:59');
        })->map(function ($group) {
            $avgWatt = $group->avg('watt');
            $kwh = round(($avgWatt / 1000), 2); // 1 jam = watt/1000 kWh
            $cost = $kwh * 13750; // tarif listrik Rp 1375 per kWh (contoh)
            return [
                'time' => Carbon::parse(optional($group->first())->timestamp ?? now())->format('H:00 - H:59'),
                'watt' => round($avgWatt, 2),
                'kwh' => $kwh,
                'cost' => round($cost, 0),
            ];
        })->values();

        // Alias biar Blade tetap bisa akses $hourlyData
        $hourlyData = $hourlyFormatted;

        // Chart data untuk grafik 24 jam
        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData = $hourlyData->pluck('watt')->toArray();

        // Total kWh dan total biaya (harian)
        $totalKwh = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');

        // --- DATA 7 HARI TERAKHIR ---
        $weeklyData = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($row) {
                $row->cost = round($row->kwh * 13750, 0);
                return $row;
            });

        $weeklyChartLabels = $weeklyData->pluck('date')->map(function ($d) {
            return Carbon::parse($d)->format('d M');
        })->toArray();
        $weeklyChartKwh = $weeklyData->pluck('kwh')->toArray();
        $weeklyChartCost = $weeklyData->pluck('cost')->toArray();

        $weeklyTotalKwh = $weeklyData->sum('kwh');
        $weeklyTotalCost = $weeklyData->sum('cost');
        $weeklyAvgKwh = $weeklyData->avg('kwh');
        $weeklyAvgWatt = $weeklyData->avg('avg_watt');

        // Kirim semua data ke Blade
        return view('dashboard.total_daya', compact(
            'hourlyData',
            'hourlyChartLabels',
            'hourlyChartData',
            'totalKwh',
            'totalCost',
            'weeklyData',
            'weeklyChartLabels',
            'weeklyChartKwh',
            'weeklyChartCost',
            'weeklyTotalKwh',
            'weeklyTotalCost',
            'weeklyAvgKwh',
            'weeklyAvgWatt'
        ));
    }

    public function sisaKwh()
    {
        // Ambil data dari MySQL untuk 24 jam terakhir
        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->get();

        $hourlyData = $records->groupBy(function ($record) {
            return Carbon::parse($record->timestamp)->format('H:00 - H:59');
        })->map(function ($group, $hour) {
            $kwh = round($group->avg('watt') / 1000, 2);
            $remaining = max(40 - $kwh, 0); // contoh: asumsi saldo awal 40 kWh
            $cost = round($kwh * 13750, 0);
            return [
                'time' => $hour,
                'remaining_kwh' => $remaining,
                'kwh' => $kwh,
                'cost' => $cost,
            ];
        })->values();

        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData = $hourlyData->pluck('remaining_kwh')->toArray();
        $totalKwh = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');

        // Tambahkan ini
        $hourlyKwh = $hourlyData->pluck('kwh')->toArray();

        return view('dashboard.sisa_kwh', compact(
            'hourlyData',
            'hourlyKwh',         // ← tambahan penting
            'totalKwh',
            'totalCost',
            'hourlyChartLabels',
            'hourlyChartData'
        ));
    }

}
