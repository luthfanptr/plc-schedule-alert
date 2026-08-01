<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlcStatus;
use Illuminate\Http\Request;

class PlcStatusController extends Controller
{
    // GET /api/status
    public function index(Request $request)
    {
        $request->validate([
            'plc_id' => 'sometimes|integer',
        ]);

        $query = PlcStatus::query();

        if ($request->has('plc_id')) {
            $query->where('plc_id', $request->plc_id);
        }

        if ($request->has('spk_number') && $request->spk_number === 'null') {
            $query->whereNull('spk_number');
        }

        return response()->json($query->paginate(15));
    }

    //PATCH /api/status/{plc_id}/spk_number
    public function updateSpkNum(Request $request, int $plc_id) 
    {
        $request->validate([
            'spk_number' => 'required|string|max:255',
        ]);

        $updated = PlcStatus::where('plc_id', $plc_id)
            ->update(['spk_number' => $request->spk_number]);

        if ($updated === 0) {
            return response()->json([
                'message' => 'PLC ID not found'
            ], 404);
        }

        return response()->json([
            'message'      => 'SPK Number successfully added',
            'total_updated' => $updated,
        ]);
    }
}
