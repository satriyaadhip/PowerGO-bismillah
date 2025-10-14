<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function totalDaya()
    {
        // Data Harian (24 Jam) - contoh data dummy
        $hourlyData = collect([
            ['time' => '00:00 - 01:00', 'watt' => 120, 'kwh' => 0.12, 'cost' => 1650],
            ['time' => '01:00 - 02:00', 'watt' => 100, 'kwh' => 0.10, 'cost' => 1375],
            ['time' => '02:00 - 03:00', 'watt' => 80, 'kwh' => 0.08, 'cost' => 1100],
            ['time' => '03:00 - 04:00', 'watt' => 70, 'kwh' => 0.07, 'cost' => 963],
            ['time' => '04:00 - 05:00', 'watt' => 60, 'kwh' => 0.06, 'cost' => 825],
            ['time' => '05:00 - 06:00', 'watt' => 90, 'kwh' => 0.09, 'cost' => 1238],
            ['time' => '06:00 - 07:00', 'watt' => 150, 'kwh' => 0.15, 'cost' => 2063],
            ['time' => '07:00 - 08:00', 'watt' => 220, 'kwh' => 0.22, 'cost' => 3025],
            ['time' => '08:00 - 09:00', 'watt' => 280, 'kwh' => 0.28, 'cost' => 3850],
            ['time' => '09:00 - 10:00', 'watt' => 300, 'kwh' => 0.30, 'cost' => 4125],
            ['time' => '10:00 - 11:00', 'watt' => 320, 'kwh' => 0.32, 'cost' => 4400],
            ['time' => '11:00 - 12:00', 'watt' => 400, 'kwh' => 0.40, 'cost' => 5500],
            ['time' => '12:00 - 13:00', 'watt' => 450, 'kwh' => 0.45, 'cost' => 6188],
            ['time' => '13:00 - 14:00', 'watt' => 420, 'kwh' => 0.42, 'cost' => 5775],
            ['time' => '14:00 - 15:00', 'watt' => 380, 'kwh' => 0.38, 'cost' => 5225],
            ['time' => '15:00 - 16:00', 'watt' => 360, 'kwh' => 0.36, 'cost' => 4950],
            ['time' => '16:00 - 17:00', 'watt' => 340, 'kwh' => 0.34, 'cost' => 4675],
            ['time' => '17:00 - 18:00', 'watt' => 420, 'kwh' => 0.42, 'cost' => 5775],
            ['time' => '18:00 - 19:00', 'watt' => 520, 'kwh' => 0.52, 'cost' => 7150],
            ['time' => '19:00 - 20:00', 'watt' => 500, 'kwh' => 0.50, 'cost' => 6875],
            ['time' => '20:00 - 21:00', 'watt' => 480, 'kwh' => 0.48, 'cost' => 6600],
            ['time' => '21:00 - 22:00', 'watt' => 350, 'kwh' => 0.35, 'cost' => 4813],
            ['time' => '22:00 - 23:00', 'watt' => 280, 'kwh' => 0.28, 'cost' => 3850],
            ['time' => '23:00 - 00:00', 'watt' => 200, 'kwh' => 0.20, 'cost' => 2750],
        ])->map(fn($item) => (object) $item);

        $totalKwh = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');
        
        $hourlyChartLabels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
        $hourlyChartData = [120, 80, 60, 150, 280, 320, 450, 380, 340, 520, 480, 280];

        // Data 7 Hari - contoh data dummy
        $weeklyData = collect([
            ['date' => 'Senin, 13 Oktober 2025', 'avg_watt' => 520, 'kwh' => 12.5, 'cost' => 171875],
            ['date' => 'Selasa, 14 Oktober 2025', 'avg_watt' => 592, 'kwh' => 14.2, 'cost' => 195250],
            ['date' => 'Rabu, 15 Oktober 2025', 'avg_watt' => 492, 'kwh' => 11.8, 'cost' => 162250],
            ['date' => 'Kamis, 16 Oktober 2025', 'avg_watt' => 650, 'kwh' => 15.6, 'cost' => 214500],
            ['date' => 'Jumat, 17 Oktober 2025', 'avg_watt' => 579, 'kwh' => 13.9, 'cost' => 191125],
            ['date' => 'Sabtu, 18 Oktober 2025', 'avg_watt' => 679, 'kwh' => 16.3, 'cost' => 224125],
            ['date' => 'Minggu, 19 Oktober 2025', 'avg_watt' => 613, 'kwh' => 14.7, 'cost' => 202125],
        ])->map(fn($item) => (object) $item);

        $weeklyTotalKwh = $weeklyData->sum('kwh');
        $weeklyTotalCost = $weeklyData->sum('cost');
        $weeklyAvgKwh = $weeklyTotalKwh / $weeklyData->count();
        $weeklyAvgWatt = $weeklyData->avg('avg_watt');

        $weeklyChartLabels = ['Sen, 13 Okt', 'Sel, 14 Okt', 'Rab, 15 Okt', 'Kam, 16 Okt', 'Jum, 17 Okt', 'Sab, 18 Okt', 'Min, 19 Okt'];
        $weeklyChartKwh = [12.5, 14.2, 11.8, 15.6, 13.9, 16.3, 14.7];
        $weeklyChartCost = [171875, 195250, 162250, 214500, 191125, 224125, 202125];

        return view('dashboard.total_daya', compact(
            'hourlyData',
            'totalKwh',
            'totalCost',
            'hourlyChartLabels',
            'hourlyChartData',
            'weeklyData',
            'weeklyTotalKwh',
            'weeklyTotalCost',
            'weeklyAvgKwh',
            'weeklyAvgWatt',
            'weeklyChartLabels',
            'weeklyChartKwh',
            'weeklyChartCost'
        ));
    }
}
