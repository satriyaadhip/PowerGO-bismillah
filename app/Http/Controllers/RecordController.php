<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * Masalah-masalah pada kode ini:
 * 1. Field-field pada validasi dan create/update tidak sesuai dengan struktur Record di project kamu
 *    - Field di validator: 'device_id', 'voltage', 'current', 'power'
 *    - Field di FirebaseController dan (kemungkinan) Record: 'device_id', 'voltage', 'amperage', 'watt', 'timestamp'
 *    - Kode ini pakai 'current' dan 'power' padahal seharusnya 'amperage' dan 'watt'
 * 2. Create: $request->all() menyimpan semua kolom mentah, bisa menyebabkan error mass assignment/security
 * 3. Timestamp tidak diset/manual, padahal mungkin dibutuhkan
 * 4. Namespace harus diawali di baris 1 file PHP (Lint error)
 */

class RecordController extends Controller
{
    // GET /api/records
    public function index()
    {
        $records = Record::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Records retrieved successfully',
            'data' => $records
        ], 200);
    }

    // GET /api/records/{id}
    public function show($id)
    {
        $record = Record::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Record found successfully',
            'data' => $record
        ], 200);
    }

    // POST /api/records
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|integer',
            'voltage'   => 'required|numeric',
            'amperage'  => 'required|numeric', // field di DB: 'amperage' (bukan 'current')
            'watt'      => 'required|numeric', // field di DB: 'watt' (bukan 'power')
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $record = Record::create([
            'device_id' => $request->input('device_id'),
            'voltage'   => $request->input('voltage'),
            'amperage'  => $request->input('amperage'),
            'watt'      => $request->input('watt'),
            'timestamp' => now(),
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $record
        ], 201);
    }

    // PUT /api/records/{id}
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|integer',
            'voltage'   => 'required|numeric',
            'amperage'  => 'required|numeric',
            'watt'      => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $record = Record::findOrFail($id);
        $record->update([
            'device_id' => $request->input('device_id'),
            'voltage'   => $request->input('voltage'),
            'amperage'  => $request->input('amperage'),
            'watt'      => $request->input('watt'),
            // Sengaja tidak update timestamp di update, biarkan historis aslinya
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $record
        ], 200);
    }

    // DELETE /api/records/{id}
    public function destroy($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted successfully'
        ], 200);
    }
}

