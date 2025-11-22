<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class DayaController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    // 🔹 Endpoint untuk baca data total daya
    public function getRealtimePower()
    {
        $data = $this->firebase->getData('powergo/realtime');
        return response()->json($data);
    }

    // 🔹 Endpoint untuk simpan data contoh (buat testing)
    public function setRealtimePower(Request $request)
    {
        $ampere = $request->input('amperage', 3.5);
        $voltage = $request->input('voltage', 220);
        $watt = $voltage * $ampere;

        $payload = [
            'amperage' => $ampere,
            'voltage' => $voltage,
            'watt' => $watt,
            'timestamp' => now()->toDateTimeString(),
        ];


        $this->firebase->setData('powergo/realtime', $payload);

        return response()->json(['success' => true, 'data' => $payload]);
    }
    // Added by assistant: simple index method to satisfy routes calling DayaController@index
public function index()
{
}

}