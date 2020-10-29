<?php


namespace App\Http\Database;


use App\Models\Device;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataDB
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetData(Request $request)
    {
        $data = Device::all();
        return response()->json($data);
    }

    public function GetAllData(Request $request)
    {
        $id = $request->User()->userID;

        // SQL query til at få fat på alle målinger for en bruger
        $result = DB::select('SELECT measurement, value, meterType FROM measurements
    WHERE deviceID IN (
        SELECT deviceID FROM devices
        WHERE householdID = (
            SELECT householdID FROM households
            WHERE userID = ?
        )
    )', [$id]);

        return $this->ConvertToObjects($result);
    }

    public function ConvertToObjects($result)
    {
        // konverter resultatet til objekter og smid i et nyt array
        $values = [];

        foreach ($result as $i => $m) {
            //værdien
            // få fat i value string
            $valueString = $m->value;

            // fjern sidste 3 karakterer
            $string = substr($valueString, 0, strlen($valueString) - 3);

            // få fat i type
            $typeString = $m->meterType;

            // konverter til int
            $value = (double)$string;

            //datoen
            $dateString = $m->measurement;
            $dateString = substr($dateString, 0, 10);

            // opret objekt
            $data = new DataStore;
            $data->date = new DateTime($dateString);
            $data->value = $value;
            $data->type = $typeString;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        return $values; // returner array af objekter
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetDataUser(Request $request, $type)
    {
        $id = $request->User()->userID;

        // SQL query til at få fat på alle målinger for en bruger
        $result = DB::select('SELECT measurement, value, meterType FROM measurements
    WHERE meterType = ? AND deviceID IN (
        SELECT deviceID FROM devices
        WHERE householdID = (
            SELECT householdID FROM households
            WHERE userID = ?
        )
    )', [$type . ' water', $id]);

        return $this->ConvertToObjects($result);
    }

    public function GetPricePrCubic(Request $request)
    {
        // Får fat i regionID fra en bruger
        $regionID = $request->User()->regionID;

        // Indhenter region oplysninger ud fra regionID
        $result = DB::select('SELECT regionID, region, pricePrCubic FROM regions WHERE regionID = ?', [$regionID]);

        // Kalder ConvertToRegionObjects funktionen, der returner et regionStore objekt
        $regionStore = $this->ConvertToRegionObject($result);

        return $regionStore;
    }

    public function ConvertToRegionObject($result)
    {
        $regionStore = new RegionStore();

        foreach ($result as $i => $r) {
            $regionStore->regionID = $r->regionID;
            $regionStore->region = $r->region;
            $regionStore->pricePrCubic = $r->pricePrCubic;
        }

        return $regionStore;
    }
}

class DataStore
{
    public $date;
    public $value;
    public $type;
}

class RegionStore
{
    public $regionID;
    public $region;
    public $pricePrCubic;
}
