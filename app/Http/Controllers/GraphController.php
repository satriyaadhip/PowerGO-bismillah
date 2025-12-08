<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Number;
use App\Helpers\Tarif;


class GraphController extends Controller
{
    /**
     * Calculate remaining kWh for a customer based on kwh_balance and actual usage
     * This ensures consistency across all methods
     * 
     * @param \App\Models\Customer $customer
     * @param Carbon|null $fromDate Start date for usage calculation (default: start of today)
     * @return float|null Remaining kWh (null for pascabayar)
     */
    protected function calculateRemainingKwh($customer, $fromDate = null)
    {
        if ($customer->billing_type !== 'prabayar') {
            return null;
        }

        $baseBalance = $customer->kwh_balance ?? 0;
        
        // Default to start of today if not specified
        if ($fromDate === null) {
            $fromDate = now()->startOfDay();
        }
        
        // Calculate total kWh used from fromDate to now
        $records = Record::where('timestamp', '>=', $fromDate)
            ->orderBy('timestamp', 'asc')
            ->get();
        
        if ($records->isEmpty()) {
            return $baseBalance;
        }
        
        // Group records by hour and calculate kWh per hour
        $hourlyUsage = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            return $ts->format("H");
        })->map(function ($group) {
            $avgWatt = $group->avg('watt');
            return round($avgWatt / 1000, 2); // kWh per hour
        });
        
        $totalKwhUsed = $hourlyUsage->sum();
        
        // Get realtime watt and calculate additional usage since last record
        $realtimeKwhAdditional = 0;
        try {
            $lastRecord = Record::orderBy('timestamp', 'desc')->first();
            if ($lastRecord) {
                $firebaseResponse = app(FirebaseController::class)->getRealtimeData();
                $data = $firebaseResponse->getData(true);
                $currentWatt = $data['watt'] ?? 0;
                
                $lastRecordTime = Carbon::parse($lastRecord->timestamp);
                $minutesSinceLastRecord = now()->diffInMinutes($lastRecordTime);
                
                // Only calculate if more than 5 minutes have passed (to avoid double counting)
                if ($minutesSinceLastRecord > 5) {
                    $hoursSinceLastRecord = $minutesSinceLastRecord / 60;
                    $realtimeKwhAdditional = ($currentWatt / 1000) * $hoursSinceLastRecord;
                }
            }
        } catch (\Exception $e) {
            // If realtime fetch fails, just use 0
        }
        
        // Remaining = base balance - total used - realtime usage
        return max($baseBalance - $totalKwhUsed - $realtimeKwhAdditional, 0);
    }

    /**
     * Update kwh_balance in database based on actual usage
     * This calculates remaining kWh and updates the database to keep it in sync
     * 
     * @param \App\Models\Customer $customer
     * @return void
     */
    public function updateKwhBalanceFromUsage($customer)
    {
        if ($customer->billing_type !== 'prabayar') {
            return;
        }

        // Calculate remaining kWh using the same logic
        $remainingKwh = $this->calculateRemainingKwh($customer);
        
        if ($remainingKwh === null) {
            return;
        }
        
        // Only update if there's a significant difference (to avoid constant updates)
        // This syncs the database with the calculated remaining kWh
        if (abs($customer->kwh_balance - $remainingKwh) > 0.01) {
            $customer->update(['kwh_balance' => $remainingKwh]);
        }
    }

    /**
     * =====================================================================
     * API ENDPOINT - UPDATE KWH BALANCE REALTIME
     * =====================================================================
     */
    public function updateKwhBalanceRealtime()
    {
        $user = auth('web')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customer = $user->customer;
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Update kwh_balance based on actual usage
        $this->updateKwhBalanceFromUsage($customer);
        
        // Refresh to get updated value
        $customer->refresh();
        
        // Calculate remaining kWh
        $remainingKwh = $this->calculateRemainingKwh($customer);

        return response()->json([
            'success' => true,
            'kwh_balance' => (float) $customer->kwh_balance,
            'remaining_kwh' => $remainingKwh !== null ? (float) $remainingKwh : null,
            'updated_at' => $customer->updated_at->toIso8601String(),
        ]);
    }

    /**
     * =====================================================================
     * HALAMAN TOTAL DAYA (DETAIL)
     * =====================================================================
     */
    public function totalDaya(Request $request)
    {
        // =============================
        // CUSTOMER DATA
        // =============================
        $user      = auth('web')->user();
        $customer  = $user->customer;

        $pelangganId = $customer->pelanggan_id ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingType = ucfirst($customer->billing_type ?? 'Prabayar');

        // Tarif dinamis berdasarkan daya VA
        $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);


        // =============================
        // DATE HANDLING
        // =============================
        $selectedDate = $request->date
            ? Carbon::parse($request->date)->startOfDay()
            : now()->startOfDay();

        $prevDate = $selectedDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $selectedDate->copy()->addDay()->format('Y-m-d');


        // =============================
        // QUERY DATA HARI INI
        // =============================
        $records = Record::whereBetween('timestamp', [
            $selectedDate->copy()->startOfDay(),
            $selectedDate->copy()->endOfDay(),
        ])
            ->orderBy('timestamp', 'asc')
            ->get();

        // LAST CHARGE — harus dibuat sebelum return view
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord
            ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i')
            : '-';

        // =============================
        // IF NO DATA
        // =============================
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
                'lastCharge'        => $lastCharge,
                // Customer Data
                'pelangganId' => $pelangganId,
                'maxPower'    => $maxPower,
                'billingType' => $billingType,
            ]);
        }


        // =============================
        // FORMAT DATA PER JAM
        // =============================
        $hourlyData = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            return $ts->format("H:00 - H:59");
        })
            ->map(function ($group, $range) use ($tarif) {
                $avgWatt = $group->avg('watt');
                $kwh     = round($avgWatt / 1000, 2);

                return [
                    'time' => $range,
                    'watt' => round($avgWatt, 2),
                    'kwh'  => $kwh,
                    'cost' => round($kwh * $tarif),
                ];
            })
            ->values();


        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('watt')->toArray();

        $totalKwh  = $hourlyData->sum('kwh');
        $totalCost = round($totalKwh * $tarif);


        // =============================
        // WEEKLY DATA (FIXED)
        // =============================
        $weeklyRaw = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $weeklyData = $weeklyRaw->map(function ($row) use ($tarif) {

            // kWh = rata-rata watt * 24 jam / 1000
            $dailyKwh = round(($row->avg_watt * 24) / 1000, 2);

            return [
                'date'     => Carbon::parse($row->date)->format('d M'),
                'avg_watt' => round($row->avg_watt, 2),
                'kwh'      => $dailyKwh,
                'cost'     => round($dailyKwh * $tarif),
            ];
        });

        $weeklyChartLabels = $weeklyData->pluck('date')->toArray();
        $weeklyChartKwh    = $weeklyData->pluck('kwh')->toArray();
        $weeklyChartCost   = $weeklyData->pluck('cost')->toArray();

        $weeklyTotalKwh  = $weeklyData->sum('kwh');
        $weeklyTotalCost = $weeklyData->sum('cost');

        // =============================
        // RETURN FINAL VIEW
        // =============================
        return view('dashboard.total_daya', [
            'selectedDate'      => $selectedDate->format('Y-m-d'),
            'prevDate'          => $prevDate,
            'nextDate'          => $nextDate,

            'hourlyData'        => $hourlyData,
            'hourlyChartLabels' => $hourlyChartLabels,
            'hourlyChartData'   => $hourlyChartData,

            'totalKwh'          => $totalKwh,
            'totalCost'         => $totalCost,

            'weeklyData'        => $weeklyData,
            'weeklyChartLabels' => $weeklyChartLabels,
            'weeklyChartKwh'    => $weeklyChartKwh,
            'weeklyChartCost'   => $weeklyChartCost,
            'weeklyTotalKwh'    => $weeklyTotalKwh,
            'weeklyTotalCost'   => $weeklyTotalCost,
            'lastCharge'        => $lastCharge,

            // Customer Data
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingType,
        ]);



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

    }

    /**
     * =====================================================================
     * HALAMAN SISA KWH
     * =====================================================================
     */
    public function sisaKwh(Request $request)
    {
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingTypeRaw = $customer->billing_type ?? 'prabayar';
        $billingType = ucfirst($billingTypeRaw);

        // Redirect pascabayar users - they shouldn't access this page
        if ($billingTypeRaw === 'pascabayar') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini hanya untuk pelanggan Prabayar.');
        }

        // Get tarif
        $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);

        // =============================
        // DATE HANDLING
        // =============================
        $selectedDate = $request->date
            ? Carbon::parse($request->date)->startOfDay()
            : now()->startOfDay();

        $prevDate = $selectedDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $selectedDate->copy()->addDay()->format('Y-m-d');

        // ============================
        // WEEKLY DATA (Same logic as total_daya)
        // ============================
        $weeklyRaw = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Update kwh_balance based on actual usage (sync to database)
        $this->updateKwhBalanceFromUsage($customer);
        // Refresh customer to get updated balance
        $customer->refresh();
        
        // Get current balance from database (after sync)
        $currentBalance = $customer->kwh_balance ?? 0;
        
        // Calculate daily remaining kWh (working backwards from current balance)
        $runningBalance = $currentBalance;
        $dailyRemainingMap = [];
        
        // Process days in reverse order (newest to oldest) to calculate remaining
        $weeklyRawReversed = $weeklyRaw->reverse();
        foreach ($weeklyRawReversed as $row) {
            $dateKey = Carbon::parse($row->date)->format('Y-m-d');
            // kWh per day = average watt * 24 hours / 1000 (same as total_daya)
            $dailyKwh = round(($row->avg_watt * 24) / 1000, 2);
            
            // Balance at start of this day = current running balance + usage of this day
            $balanceAtStart = $runningBalance + $dailyKwh;
            // Remaining at end of day = balance at start - usage
            $remainingAtEnd = max($balanceAtStart - $dailyKwh, 0);
            
            $dailyRemainingMap[$dateKey] = [
                'remaining_kwh' => $remainingAtEnd,
                'balance_at_start' => $balanceAtStart,
            ];
            
            // Update running balance for next (older) day
            $runningBalance = $balanceAtStart;
        }
        
        $weeklyData = $weeklyRaw->map(function ($row) use ($tarif, $dailyRemainingMap) {
            $dateKey = Carbon::parse($row->date)->format('Y-m-d');
            // kWh per day = average watt * 24 hours / 1000 (same as total_daya)
            $dailyKwh = round(($row->avg_watt * 24) / 1000, 2);
            $remaining = $dailyRemainingMap[$dateKey]['remaining_kwh'] ?? 0;

            return [
                'date'     => Carbon::parse($row->date)->format('d M'),
                'avg_watt' => round($row->avg_watt, 2),
                'kwh'      => $dailyKwh,
                'cost'     => round($dailyKwh * $tarif),
                'remaining_kwh' => $remaining,
            ];
        });
        
        // Chart data for daily remaining kWh
        $dailyChartLabels = $weeklyData->pluck('date')->toArray();
        $dailyChartData = $weeklyData->pluck('remaining_kwh')->toArray();

        // DATA MINGGUAN
        // $weeklyData = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
        //     ->where('timestamp', '>=', now()->subDays(7))
        //     ->groupBy('date')
        //     ->orderBy('date', 'asc')
        //     ->get()
        //     ->map(function ($row) use ($tarif) {
        //         return [
        //             'date'     => Carbon::parse($row->date)->format('d M'),
        //             'avg_watt' => round($row->avg_watt, 2),
        //             'kwh'      => round($row->kwh, 2),
        //             'cost'     => round($row->kwh * $tarif),
        //         ];
        //     });

        // =============================
        // QUERY DATA HARI INI
        // =============================
        $records = Record::whereBetween('timestamp', [
            $selectedDate->copy()->startOfDay(),
            $selectedDate->copy()->endOfDay(),
        ])
            ->orderBy('timestamp', 'asc')
            ->get();

        // LAST CHARGE
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord
            ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i')
            : '-';

        // =============================
        // IF NO DATA
        // =============================
        if ($records->isEmpty()) {
            return view('dashboard.sisa_kwh', [
                'selectedDate'      => $selectedDate->format('Y-m-d'),
                'prevDate'          => $prevDate,
                'nextDate'          => $nextDate,
                'hourlyData'        => [],
                'hourlyChartLabels' => [],
                'hourlyChartData'   => [],
                'weeklyData'        => [],
                'dailyChartLabels'  => [],
                'dailyChartData'    => [],
                'totalKwh'          => 0,
                'totalCost'         => 0,
                'lastCharge'        => $lastCharge,
                // Customer
                'pelangganId' => $pelangganId,
                'maxPower'    => $maxPower,
                'billingType' => $billingTypeRaw,
                'billingTypeDisplay' => $billingType,
            ]);
        }

        // Get current kwh_balance for remaining calculation (already calculated above)

        // =============================
        // FORMAT DATA PER JAM
        // =============================
        // Calculate remaining kWh for each hour (accumulative)
        $runningKwhUsed = 0;
        $hourlyData = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            $start = $ts->format("H:00");
            $end = $ts->format("H:59");
            return "$start - $end";
        })->map(function ($group, $range) use ($currentBalance, $tarif, &$runningKwhUsed) {
            $kwh = round($group->avg('watt') / 1000, 2);
            $runningKwhUsed += $kwh; // Accumulate usage
            return [
                'time' => $range,
                'remaining_kwh' => max($currentBalance - $runningKwhUsed, 0),
                'kwh'  => $kwh,
                'cost' => round($kwh * $tarif, 0),
            ];
        })->values();

        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('remaining_kwh')->toArray();
        $hourlyKwh         = $hourlyData->pluck('kwh')->toArray();
        $totalKwh          = $hourlyData->sum('kwh');
        $totalCost         = $hourlyData->sum('cost');


        return view('dashboard.sisa_kwh', [
            'selectedDate'      => $selectedDate->format('Y-m-d'),
            'prevDate'          => $prevDate,
            'nextDate'          => $nextDate,
            'hourlyData'        => $hourlyData,
            'hourlyChartLabels' => $hourlyChartLabels,
            'hourlyChartData'  => $hourlyChartData,
            'hourlyKwh'        => $hourlyKwh,
            'totalKwh'         => $totalKwh,
            'totalCost'        => $totalCost,
            'weeklyData'       => $weeklyData,
            'dailyChartLabels' => $dailyChartLabels,
            'dailyChartData'   => $dailyChartData,
            'lastCharge'       => $lastCharge,
            // Customer
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingTypeRaw,
            'billingTypeDisplay' => $billingType,
        ]);
    }



    // public function sisaKwh()
    // {
    //     // CUSTOMER DATA
    //     $user = auth('web')->user();
    //     $customer = $user->customer;
    //     $pelangganId = $customer->pelanggan_id ?? '-';
    //     $maxPower    = $customer->daya_va ?? 1300;
    //     $billingTypeRaw = $customer->billing_type ?? 'prabayar';
    //     $billingType = ucfirst($billingTypeRaw);

    //     // Redirect pascabayar users - they shouldn't access this page
    //     if ($billingTypeRaw === 'pascabayar') {
    //         return redirect()->route('dashboard')->with('error', 'Halaman ini hanya untuk pelanggan Prabayar.');
    //     }

    //     // Get tarif
    //     $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);

    //     // DATA MINGGUAN - Fixed calculation: Use same method as totalDaya
    //     $weeklyRaw = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
    //         ->where('timestamp', '>=', now()->subDays(7))
    //         ->groupBy('date')
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     $weeklyData = $weeklyRaw->map(function ($row) use ($tarif) {
    //         $kwh = round($row->kwh, 2);
    //         return [
    //             'date'     => Carbon::parse($row->date)->format('d M'),
    //             'avg_watt' => round($row->avg_watt, 2),
    //             'kwh'      => $kwh,
    //             'cost'     => round($kwh * $tarif),
    //         ];
    //     });

    //     // RECORD 24 JAM
    //     $records = Record::where('timestamp', '>=', now()->subDay())
    //         ->orderBy('timestamp', 'asc')
    //         ->get();

    //     // Get current kwh_balance for remaining calculation
    //     $currentBalance = $customer->kwh_balance ?? 0;

    //     $hourlyData = $records->groupBy(function ($record) {
    //         $ts = Carbon::parse($record->timestamp);
    //         $start = $ts->format("H:00");
    //         $end = $ts->format("H:59");
    //         return "$start - $end";
    //     })->map(function ($group, $range) use ($tarif, $currentBalance) {
    //         $avgWatt = $group->avg('watt');
    //         $kwh = round($avgWatt / 1000, 2);
    //         // Calculate remaining: balance minus usage in this hour
    //         $remaining = max($currentBalance - $kwh, 0);
    //         return [
    //             'time' => $range,
    //             'watt' => round($avgWatt, 2),
    //             'remaining_kwh' => $remaining,
    //             'kwh'  => $kwh,
    //             'cost' => round($kwh * $tarif, 0),
    //         ];
    //     })->values();

    //     $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
    //     $hourlyChartData   = $hourlyData->pluck('remaining_kwh')->toArray();
    //     $hourlyKwh         = $hourlyData->pluck('kwh')->toArray();
    //     $totalKwh          = $hourlyData->sum('kwh');
    //     $totalCost         = $hourlyData->sum('cost');


    //     return view('dashboard.sisa_kwh', [
    //         'weeklyData'       => $weeklyData,
    //         'hourlyData'       => $hourlyData,
    //         'hourlyChartLabels'=> $hourlyChartLabels,
    //         'hourlyChartData'  => $hourlyChartData,
    //         'hourlyKwh'        => $hourlyKwh,
    //         'totalKwh'         => $totalKwh,
    //         'totalCost'        => $totalCost,

    //         // Customer
    //         'pelangganId' => $pelangganId,
    //         'maxPower'    => $maxPower,
    //         'billingType' => $billingTypeRaw,
    //         'billingTypeDisplay' => $billingType,
    //     ]);
    // }

    public function pembayaran()
    {
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $nama        = $user->name ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingTypeRaw = $customer->billing_type ?? 'prabayar';
        $billingType = ucfirst($billingTypeRaw);

        // Tarif dinamis berdasarkan daya VA
        $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);

        // Update kwh_balance based on actual usage (sync to database)
        if ($billingTypeRaw === 'prabayar') {
            $this->updateKwhBalanceFromUsage($customer);
            $customer->refresh();
        }

        // Sisa kWh calculation (for Prabayar) - use helper for consistency
        $remainingKwh = $this->calculateRemainingKwh($customer);

        // LAST CHARGE
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord ? Carbon::parse($lastRecord->timestamp)->translatedFormat('d F Y') : '-';

        // BILL DATA FOR PASCABAYAR
        $billData = null;
        if ($billingTypeRaw === 'pascabayar') {
            // Calculate monthly usage (last 30 days or current month)
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();

            $monthlyRecords = Record::whereBetween('timestamp', [$startDate, $endDate])->get();

            if ($monthlyRecords->isEmpty()) {
                // Fallback to last 30 days if no data this month
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $monthlyRecords = Record::whereBetween('timestamp', [$startDate, $endDate])->get();
            }

            $totalWatt = $monthlyRecords->sum('watt');
            $totalKwh = round($totalWatt / 1000, 2);
            $totalCost = round($totalKwh * $tarif);

            $billData = [
                'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                'totalKwh' => $totalKwh,
                'totalCost' => $totalCost,
                'tarifPerKwh' => $tarif,
            ];
        }

        return view('pembayaran.pembayaran', [
            'sisaKwh'      => $remainingKwh,
            'pelangganId'  => $pelangganId,
            'nama'         => $nama,
            'jenis'        => $maxPower . ' VA - ' . $billingType,
            'isiTerakhir'  => $lastCharge,
            'billingType'  => $billingTypeRaw, // Pass raw for condition checking
            'billingTypeDisplay' => $billingType, // Pass formatted for display
            'billData'     => $billData, // For Pascabayar
        ]);
    }

    /**
     * =====================================================================
     * HALAMAN PEMBAYARAN LANJUT (CONFIRMATION)
     * =====================================================================
     */
    public function pembayaranLanjut(Request $request)
    {
        // CUSTOMER DATA
        $user = auth('web')->user();
        $customer = $user->customer;
        $pelangganId = $customer->pelanggan_id ?? '-';
        $nama        = $user->name ?? '-';
        $maxPower    = $customer->daya_va ?? 1300;
        $billingTypeRaw = $customer->billing_type ?? 'prabayar';
        $billingType = ucfirst($billingTypeRaw);

        // Get payment data from request
        $selectedAmountStr = $request->input('amount', 'Rp100.000');
        $selectedKwh = $request->input('kwh', '68.18');
        $selectedPaymentMethod = $request->input('method', 'QRIS');

        // Parse amount (remove "Rp" and dots, convert to number)
        $amount = (float) str_replace(['Rp', '.', ' '], '', $selectedAmountStr);
        $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);

        // For Prabayar: Calculate kWh from amount and save transaction
        if ($billingTypeRaw === 'prabayar') {
            $kwhToAdd = round($amount / $tarif, 2);

            // Save transaction
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'billing_type' => $billingTypeRaw,
                'amount' => $amount,
                'kwh' => $kwhToAdd,
                'payment_method' => $selectedPaymentMethod,
                'status' => 'completed',
            ]);

            // Update customer kwh_balance
            $customer->increment('kwh_balance', $kwhToAdd);

            $selectedKwh = number_format($kwhToAdd, 2);
        } else {
            // For Pascabayar: Get bill data and save transaction
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();

            $monthlyRecords = Record::whereBetween('timestamp', [$startDate, $endDate])->get();

            if ($monthlyRecords->isEmpty()) {
                $startDate = now()->subDays(30)->startOfDay();
                $endDate = now()->endOfDay();
                $monthlyRecords = Record::whereBetween('timestamp', [$startDate, $endDate])->get();
            }

            $totalWatt = $monthlyRecords->sum('watt');
            $totalKwh = round($totalWatt / 1000, 2);
            $totalCost = round($totalKwh * $tarif);

            // Save transaction (no kWh for pascabayar)
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'billing_type' => $billingTypeRaw,
                'amount' => $totalCost,
                'kwh' => null,
                'payment_method' => $selectedPaymentMethod,
                'status' => 'completed',
            ]);

            $billData = [
                'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                'totalKwh' => $totalKwh,
                'totalCost' => $totalCost,
                'tarifPerKwh' => $tarif,
            ];
            $selectedAmountStr = 'Rp ' . number_format($totalCost, 0, ',', '.');
        }

        return view('pembayaran.lanjut', [
            'pelangganId' => $pelangganId,
            'nama' => $nama,
            'jenis' => $maxPower . ' VA - ' . $billingType,
            'billingType' => $billingTypeRaw,
            'billingTypeDisplay' => $billingType,
            'selectedAmount' => $selectedAmountStr,
            'selectedKwh' => $selectedKwh,
            'selectedPaymentMethod' => $selectedPaymentMethod,
            'billData' => $billData ?? null,
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
        $billingTypeRaw = $customer->billing_type ?? 'prabayar';
        $billingType = ucfirst($billingTypeRaw);

        // TOTAL DAYA SUMMARY
        $records = Record::where('timestamp', '>=', now()->subDay())->orderBy('timestamp', 'asc')->get();

        // For chart data (24 jam)
        $tarif = \App\Helpers\Tarif::getTarifPerKwh($maxPower);
        $hourlyData = $records->groupBy(function ($record) {
            $ts = Carbon::parse($record->timestamp);
            return $ts->format("H:00 - H:59");
        })->map(function ($group, $range) use ($tarif) {
            $avgWatt = $group->avg('watt');
            $kwh     = round($avgWatt / 1000, 2);
            return [
                'time' => $range,
                'watt' => round($avgWatt, 2),
                'kwh'  => $kwh,
                'cost' => round($kwh * $tarif),
            ];
        })->values();
        $hourlyChartLabels = $hourlyData->pluck('time')->toArray();
        $hourlyChartData   = $hourlyData->pluck('watt')->toArray();

        // WEEKLY DATA
        $weeklyRaw = Record::selectRaw('DATE(timestamp) as date, AVG(watt) as avg_watt, SUM(watt)/1000 as kwh')
            ->where('timestamp', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        $weeklyData = $weeklyRaw->map(function ($row) use ($tarif) {
            $kwh = round($row->kwh, 2);
            return [
                'date'     => Carbon::parse($row->date)->format('d M'),
                'avg_watt' => round($row->avg_watt, 2),
                'kwh'      => $kwh,
                'cost'     => round($kwh * $tarif),
            ];
        });
        $weeklyChartLabels = $weeklyData->pluck('date')->toArray();
        $weeklyChartKwh    = $weeklyData->pluck('kwh')->toArray();
        $weeklyChartCost   = $weeklyData->pluck('cost')->toArray();

        // SUMMARY NUMBERS
        $avgWatt = $records->avg('watt') ?? 0;
        $avgKwh  = $avgWatt / 1000;
        $avgCost = round($avgKwh * $tarif);
        $realtimeWatt = 0;
        try {
            $firebaseResponse = app(FirebaseController::class)->getRealtimeData();
            $data = $firebaseResponse->getData(true);
            $realtimeWatt = round($data['watt'] ?? 0);
        } catch (\Exception $e) {
        }

        // LAST CHARGE (from Record)
        $lastRecord = Record::orderBy('timestamp', 'desc')->first();
        $lastCharge = $lastRecord ? Carbon::parse($lastRecord->timestamp)->format('d/m/Y H:i') : '-';

        // LAST TOPUP (from Transaction - for Prabayar only)
        $lastTopup = '-';
        if ($billingTypeRaw === 'prabayar') {
            $lastTransaction = Transaction::where('customer_id', $customer->id)
                ->where('billing_type', 'prabayar')
                ->where('status', 'completed')
                ->whereNotNull('kwh')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastTransaction) {
                $lastTopup = Carbon::parse($lastTransaction->created_at)->format('d/m/Y H:i');
            }
        }

        // Update kwh_balance based on actual usage (sync to database)
        if ($billingTypeRaw === 'prabayar') {
            $this->updateKwhBalanceFromUsage($customer);
            // Refresh customer to get updated balance
            $customer->refresh();
        }

        // Remaining kWh: Use helper method for consistency
        $remainingKwh = $this->calculateRemainingKwh($customer);

        return view('dashboard.dashboard', [
            'realtimeWatt' => $realtimeWatt,
            'avgWatt'      => round($avgWatt),
            'avgCost'      => $avgCost,
            'remainingKwh' => $remainingKwh,
            'lastCharge'   => $lastCharge,
            'lastTopup'    => $lastTopup,
            'hourlyChartLabels' => $hourlyChartLabels,
            'hourlyChartData'   => $hourlyChartData,
            'weeklyChartLabels' => $weeklyChartLabels,
            'weeklyChartKwh'    => $weeklyChartKwh,
            'weeklyChartCost'   => $weeklyChartCost,
            // Customer
            'pelangganId' => $pelangganId,
            'maxPower'    => $maxPower,
            'billingType' => $billingTypeRaw, // Pass raw for condition checking
            'billingTypeDisplay' => $billingType,
        ]);
    }
}
