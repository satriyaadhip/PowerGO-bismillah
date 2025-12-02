<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Support\Number;

class GraphController extends Controller
{
    /**
     * =====================================================================
     * HALAMAN TOTAL DAYA (DETAIL)
     * =====================================================================
     */
    public function totalDaya(Request $request)
    {
        // Pagination 24 record (per 24 jam)
        $records = Record::where('timestamp', '>=', now()->startOfDay())
            ->orderBy('timestamp', 'asc')
            ->paginate(24);

        if ($records->isEmpty()) {
            return view('dashboard.total_daya', [
                'hourlyData'        => [],
                'hourlyChartLabels' => [],
                'hourlyChartData'   => [],
                'weeklyData'        => [],
                'weeklyChartLabels' => [],
                'weeklyChartKwh'    => [],
                'weeklyChartCost'   => [],
                'weeklyTotalKwh'    => 0,
                'weeklyTotalCost'   => 0,
                'totalKwh'          => 0,
                'totalCost'         => 0,
                'paginator'         => $records,
            ]);
        }

        /**
         * ===============================
         * FORMAT DATA 24 JAM
         * ===============================
         */
        $hourlyData = $records->groupBy(function ($record) {
            return Carbon::parse($record->timestamp)->format('H:00 - H:59');
        })->map(function ($group) {
            $avgWatt = $group->avg('watt');
            $kwh     = round($avgWatt / 1000, 2);

            return [
                'time' => Carbon::parse($group->first()->timestamp)->format('H:00'),
                'watt' => round($avgWatt, 2),
                'kwh'  => $kwh,
                'cost' => round($kwh * 13750),
            ];
        })->values();

        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('watt')->toArray();

        $totalKwh  = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');

        /**
         * ===============================
         * DATA 7 HARI TERAKHIR
         * ===============================
         */
        $weeklyRaw = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $weeklyData = $weeklyRaw->map(function ($row) {
            return [
                'date'     => Carbon::parse($row->date)->format('d M'),
                'avg_watt' => round($row->avg_watt, 2),
                'kwh'      => round($row->kwh, 2),
                'cost'     => round($row->kwh * 13750),
            ];
        });

        $weeklyChartLabels = $weeklyData->pluck('date')->toArray();
        $weeklyChartKwh    = $weeklyData->pluck('kwh')->toArray();
        $weeklyChartCost   = $weeklyData->pluck('cost')->toArray();

        $weeklyTotalKwh  = $weeklyData->sum('kwh');
        $weeklyTotalCost = Number::currency($weeklyData->sum('cost'), in: 'IDR', locale: 'id');

        $data = compact(
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
            'weeklyTotalCost'
        );
        $data['paginator'] = $records;

        return view('dashboard.total_daya', $data);
    }


    /**
     * =====================================================================
     * HALAMAN SISA KWH
     * =====================================================================
     */
    public function sisaKwh()
    {
        // DATA MINGGUAN (7 hari terakhir)
        $weeklyData = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($row) {
                return [
                    'date'     => Carbon::parse($row->date)->format('d M'),
                    'avg_watt' => round($row->avg_watt, 2),
                    'kwh'      => round($row->kwh, 2),
                    'cost'     => round($row->kwh * 13750, 0),
                ];
            });

        // RECORD 24 JAM (urut asc untuk table/graph)
        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->get();

        // Format per jam (H:00 - H:59)
        $hourlyData = $records->groupBy(function ($record) {
            return Carbon::parse($record->timestamp)->format('H:00 - H:59');
        })->map(function ($group, $hour) {
            $kwh = round($group->avg('watt') / 1000, 2);
            return [
                'time' => $hour,
                'remaining_kwh' => max(40 - $kwh, 0),
                'kwh' => $kwh,
                'cost' => round($kwh * 13750, 0),
            ];
        })->values();

        // Chart & totals
        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData = $hourlyData->pluck('remaining_kwh')->toArray();
        $hourlyKwh = $hourlyData->pluck('kwh')->toArray();
        $totalKwh = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');

        return view('dashboard.sisa_kwh', [
            'weeklyData' => $weeklyData,
            'hourlyData' => $hourlyData,
            'hourlyChartLabels' => $hourlyChartLabels,
            'hourlyChartData' => $hourlyChartData,
            'hourlyKwh' => $hourlyKwh,
            'totalKwh' => $totalKwh,
            'totalCost' => $totalCost,
        ]);
    }


    /**
     * =====================================================================
     * SUMMARY UNTUK DASHBOARD
     * =====================================================================
     */
    public function getTotalDayaSummary()
    {
        $records = Record::where('timestamp', '>=', now()->subDay())->get();

        if ($records->isEmpty()) {
            return [
                'avgWatt' => 0,
                'avgWh'   => 0,
                'totalKwh' => 0,
            ];
        }

        return [
            'avgWatt' => round($records->avg('watt')),
            'avgWh'   => round(($records->avg('watt') / 1000) * 1000),
            'totalKwh' => round($records->sum('watt') / 1000, 2),
        ];
    }

    public function getSisaKwhSummary()
    {
        $records = Record::where('timestamp', '>=', now()->subDay())->get();

        if ($records->isEmpty()) {
            return ['remainingKwh' => 0];
        }

        $used = round($records->avg('watt') / 1000, 2);

        return ['remainingKwh' => max(40 - $used, 0)];
    }


    /**
     * =====================================================================
     * DASHBOARD SUMMARY
     * =====================================================================
     */
    public function summary()
    {
        $daya = $this->getTotalDayaSummary();
        $sisa = $this->getSisaKwhSummary();

        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->paginate(24);

        $hourlyData = $records->groupBy(function ($record) {
            return Carbon::parse($record->timestamp)->format('H:00 - H:59');
        })->map(function ($group) {
            $avgWatt = $group->avg('watt');
            $kwh     = round($avgWatt / 1000, 2);

            return [
                'time' => Carbon::parse($group->first()->timestamp)->format('H:00'),
                'watt' => round($avgWatt, 2),
                'kwh'  => $kwh,
                'cost' => round($kwh * 13750),
            ];
        })->values();

        $totalCost = $hourlyData->sum('cost');

        // Realtime Firebase
        $realtimeWatt = 0;
        try {
            $firebaseResponse = app(FirebaseController::class)->getRealtimeData();
            $data = $firebaseResponse->getData(true);
            $realtimeWatt = round($data['watt'] ?? 0);
        } catch (\Exception $e) {
        }

        $avgKwh = $daya['avgWatt'] / 1000;
        $avgCost = round($avgKwh * 13750);  // tarif listrik


        // Last charge
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord
            ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i')
            : '-';

        return view('dashboard.dashboard', [
            'realtimeWatt' => $realtimeWatt,
            'avgWatt'      => $daya['avgWatt'],
            'avgCost'      => $avgCost,
            'avgWh'        => $daya['avgWh'],
            'remainingKwh' => $sisa['remainingKwh'],
            'lastCharge'   => $lastCharge,
        ]);
    }
}
