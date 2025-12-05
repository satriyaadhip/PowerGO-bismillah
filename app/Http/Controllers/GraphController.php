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
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingType = ucfirst($customer->billing_type ?? 'Prabayar');

        // Ambil tanggal
        $selectedDate = $request->date
            ? Carbon::parse($request->date)->startOfDay()
            : now()->startOfDay();

        $prevDate = $selectedDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $selectedDate->copy()->addDay()->format('Y-m-d');

        // QUERY DATA
        $records = Record::whereBetween('timestamp', [
            $selectedDate->copy()->startOfDay(),
            $selectedDate->copy()->endOfDay(),
        ])->orderBy('timestamp', 'asc')->get();

        if ($records->isEmpty()) {
            return view('dashboard.total_daya', [
                'selectedDate'      => $selectedDate->format('Y-m-d'),
                'prevDate'          => $prevDate,
                'nextDate'          => $nextDate,
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

                // Customer data
                'pelangganId' => $pelangganId,
                'maxPower'    => $maxPower,
                'billingType' => $billingType,
            ]);
        }

        // FORMAT 1 JAM
        $hourlyData = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            $start = $ts->format("H:00");
            $end   = $ts->format("H:59");
            return "$start - $end";
        })->map(function ($group, $range) {
            $avgWatt = $group->avg('watt');
            $kwh     = round($avgWatt / 1000, 2);

            return [
                'time' => $range,
                'watt' => round($avgWatt, 2),
                'kwh'  => $kwh,
                'cost' => round($kwh * 13750),
            ];
        })->values();

        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('watt')->toArray();

        $totalKwh  = $hourlyData->sum('kwh');
        $totalCost = $hourlyData->sum('cost');

// FORMAT 30 MENIT
// $hourlyData = $records->groupBy(function ($record) {
//     $ts = Carbon::parse($record->timestamp);
//     $minute = $ts->minute < 30 ? '00' : '30';

//     $start = $ts->format("H:$minute");
//     $end   = $minute === '00'
//         ? $ts->format("H:30")
//         : $ts->copy()->addHour()->format("H:00");

//     return "$start - $end";
// })->map(function ($group, $range) {
//     $avgWatt = $group->avg('watt');
//     $kwh     = round($avgWatt / 1000, 2);

//     return [
//         'time' => $range,
//         'watt' => round($avgWatt, 2),
//         'kwh'  => $kwh,
//         'cost' => round($kwh * 13750),
//     ];
// })->values();

// $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
// $hourlyChartData   = $hourlyData->pluck('watt')->toArray();

// $totalKwh  = $hourlyData->sum('kwh');
// $totalCost = $hourlyData->sum('cost');

        // DATA 7 HARI
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
        $weeklyTotalKwh    = $weeklyData->sum('kwh');
        $weeklyTotalCost   = Number::currency($weeklyData->sum('cost'), in: 'IDR', locale: 'id');

        // LAST CHARGE
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord
            ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i')
            : '-';

        return view('dashboard.total_daya', [
            'paginator' => $records,
            'selectedDate' => $selectedDate->toDateString(),
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,

            'hourlyData'        => $hourlyData,
            'hourlyChartLabels' => $hourlyChartLabels,
            'hourlyChartData'   => $hourlyChartData,

            'totalKwh'          => $totalKwh,
            'totalCost'         => $totalCost,

            // Expose lastCharge to view
            'lastCharge'        => $lastCharge,

            'weeklyData'        => $weeklyData,
            'weeklyChartLabels' => $weeklyChartLabels,
            'weeklyChartKwh'    => $weeklyChartKwh,
            'weeklyChartCost'   => $weeklyChartCost,
            'weeklyTotalKwh'    => $weeklyTotalKwh,
            'weeklyTotalCost'   => $weeklyTotalCost,

            // Customer
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingType,
        ]);
    }

    /**
     * =====================================================================
     * HALAMAN SISA KWH
     * =====================================================================
     */
    public function sisaKwh()
    {
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingType = ucfirst($customer->billing_type ?? 'Prabayar');

        // DATA MINGGUAN
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
                    'cost'     => round($row->kwh * 13750),
                ];
            });

        // RECORD 24 JAM
        $records = Record::where('timestamp', '>=', now()->subDay())
            ->orderBy('timestamp', 'asc')
            ->get();

        $hourlyData = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            $start = $ts->format("H:00");
            $end = $ts->format("H:59");
            return "$start - $end";
        })->map(function ($group, $range) {
            $kwh = round($group->avg('watt') / 1000, 2);
            return [
                'time' => $range,
                'remaining_kwh' => max(40 - $kwh, 0),
                'kwh'  => $kwh,
                'cost' => round($kwh * 13750, 0),
            ];
        })->values();

        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('remaining_kwh')->toArray();
        $hourlyKwh         = $hourlyData->pluck('kwh')->toArray();
        $totalKwh          = $hourlyData->sum('kwh');
        $totalCost         = $hourlyData->sum('cost');
        

        return view('dashboard.sisa_kwh', [
            'weeklyData'       => $weeklyData,
            'hourlyData'       => $hourlyData,
            'hourlyChartLabels'=> $hourlyChartLabels,
            'hourlyChartData'  => $hourlyChartData,
            'hourlyKwh'        => $hourlyKwh,
            'totalKwh'         => $totalKwh,
            'totalCost'        => $totalCost,

            // Customer
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingType,
        ]);
    }
    // public function totalDaya(Request $request)
    // {
    // //     /**
    //      * ======================================
    //      * PAGINATION & DATA HARI INI (24 JAM)
    //      * ======================================
    //      */
    //     $records = Record::where('timestamp', '>=', now()->startOfDay())
    //         ->orderBy('timestamp', 'asc')
    //         ->paginate(24);
    
    //     // Jika Tidak Ada Data
    //     if ($records->isEmpty()) {
    //         return view('dashboard.total_daya', [
    //             'hourlyData'         => [],
    //             'hourlyChartLabels' => [],
    //             'hourlyChartData'   => [],
    //             'weeklyData'        => [],
    //             'weeklyChartLabels'=> [],
    //             'weeklyChartKwh'    => [],
    //             'weeklyChartCost'   => [],
    //             'weeklyTotalKwh'    => 0,
    //             'weeklyTotalCost'   => 0,
    //             'totalKwh'          => 0,
    //             'totalCost'         => 0,
    //             'paginator'         => $records,
    //         ]);
    //     }
    
    //     /**
    //      * ======================================
    //      * FORMAT DATA PER 30 MENIT (24 JAM)
    //      * ======================================
    //      */
    //     $hourlyData = $records->groupBy(function ($record) {
    //         $ts = Carbon::parse($record->timestamp);
    
    //         $minute = $ts->minute < 30 ? '00' : '30';
    //         $start  = $ts->format('H:' . $minute);
    
    //         if ($minute === '00') {
    //             $end = $ts->format('H:30');
    //         } else {
    //             $end = $ts->copy()->addHour()->format('H:00');
    //         }
    
    //         return "$start - $end";
    //     })
    //     ->map(function ($group, $range) {
    //         $avgWatt = $group->avg('watt');
    //         $kwh     = round($avgWatt / 1000, 2);
    
    //         return [
    //             'time' => $range,
    //             'watt' => round($avgWatt, 2),
    //             'kwh'  => $kwh,
    //             'cost' => round($kwh * 13750),
    //         ];
    //     })
    //     ->values();
    
    //     $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
    //     $hourlyChartData   = $hourlyData->pluck('watt')->toArray();
    //     $totalKwh          = $hourlyData->sum('kwh');
    //     $totalCost         = $hourlyData->sum('cost');
    
    //     /**
    //      * ======================================
    //      * DATA 7 HARI TERAKHIR
    //      * ======================================
    //      */
    //     $weeklyRaw = Record::selectRaw('
    //             DATE(timestamp) as date, 
    //             AVG(watt) as avg_watt, 
    //             SUM(watt)/1000 as kwh
    //         ')
    //         ->where('timestamp', '>=', now()->subDays(7))
    //         ->groupBy('date')
    //         ->orderBy('date', 'asc')
    //         ->get();
    
    //     $weeklyData = $weeklyRaw->map(function ($row) {
    //         return [
    //             'date'     => Carbon::parse($row->date)->format('d M'),
    //             'avg_watt' => round($row->avg_watt, 2),
    //             'kwh'      => round($row->kwh, 2),
    //             'cost'     => round($row->kwh * 13750),
    //         ];
    //     });
    
    //     $weeklyChartLabels = $weeklyData->pluck('date')->toArray();
    //     $weeklyChartKwh    = $weeklyData->pluck('kwh')->toArray();
    //     $weeklyChartCost   = $weeklyData->pluck('cost')->toArray();
    
    //     $weeklyTotalKwh  = $weeklyData->sum('kwh');
    //     $weeklyTotalCost = Number::currency(
    //         $weeklyData->sum('cost'),
    //         in: 'IDR',
    //         locale: 'id'
    //     );
    
    //     /**
    //      * ======================================
    //      * KIRIM KE VIEW
    //      * ======================================
    //      */
    //     $data = compact(
    //         'hourlyData',
    //         'hourlyChartLabels',
    //         'hourlyChartData',
    //         'totalKwh',
    //         'totalCost',
    //         'weeklyData',
    //         'weeklyChartLabels',
    //         'weeklyChartKwh',
    //         'weeklyChartCost',
    //         'weeklyTotalKwh',
    //         'weeklyTotalCost'
    //     );
    
    //     $data['paginator'] = $records;
    
    //     return view('dashboard.total_daya', $data);
    // }
    
    /**
     * =====================================================================
     * DASHBOARD SUMMARY
     * =====================================================================
     */
    public function summary()
    {
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingType = ucfirst($customer->billing_type ?? 'Prabayar');

        // TOTAL DAYA SUMMARY
        $records = Record::where('timestamp', '>=', now()->subDay())->get();

        $avgWatt = $records->avg('watt') ?? 0;
        $avgKwh  = $avgWatt / 1000;
        $avgCost = round($avgKwh * 13750);

        // REALTIME WATT
        $realtimeWatt = 0;
        try {
            $firebaseResponse = app(FirebaseController::class)->getRealtimeData();
            $data = $firebaseResponse->getData(true);
            $realtimeWatt = round($data['watt'] ?? 0);
        } catch (\Exception $e) {}

        // LAST CHARGE
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord
            ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i')
            : '-';

        return view('dashboard.dashboard', [
            'realtimeWatt' => $realtimeWatt,
            'avgWatt'      => round($avgWatt),
            'avgCost'      => $avgCost,
            'remainingKwh' => max(40 - $avgKwh, 0),
            'lastCharge'   => $lastCharge,

            // Customer
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingType,
        ]);
    }
}
