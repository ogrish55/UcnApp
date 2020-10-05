<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeasurementController extends Controller
{
    public function measurement($id)
    {
        $results = DB::select('
        SELECT * FROM measurements WHERE deviceID = (
        SELECT deviceID FROM devices
        WHERE householdID = (
        SELECT householdID FROM Households
        WHERE userID = :id
        ) LIMIT 1,1 )', ['id' => $id]);

        dd($results);

//        return response()->json([
//           $results
//        ]);
    }

}
