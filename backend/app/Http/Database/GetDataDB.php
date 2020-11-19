<?php


namespace App\Http\Database;

use App\Models\Region;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataDB
{
    private function ConvertToObjects($result)
    {
        $objectCollection = [];

        // Iterer igennem hver objekt i $result kollektionen.
        foreach ($result as $m) {
            $data = new DataStore(new DateTime($m->measurement), (double)$m->value, $m->meterType); // Opret DataStore objekt med Dato, måling, og målingsType.
            $objectCollection[] = $data;                                                            // tilføj objektet til arrayet
        }
        return $objectCollection;
    }

    public function GetAconto(Request $request)
    {
        $userID = $request->User()->userID;

        $result = DB::select('SELECT aconto from users WHERE userID = ?', [$userID]);

        return $result;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $type
     * @return array
     */
    public function GetMeasurementsBasedOnType(Request $request, $type)
    {
        $id = $request->User()->userID;

        // if $type is null or 'all'
        if ($type == 'all' || $type == null) {
            $result = DB::select('SELECT measurement, value, meterType FROM measurements
                WHERE deviceID IN (
                    SELECT deviceID FROM devices
                        WHERE householdID = (
                            SELECT householdID FROM households
                                WHERE userID = ?))',
                [$id]);
            return $this->ConvertToObjects($result);
        } // if $type is 'hot' or 'cold'
        else {
            $result = DB::select('SELECT measurement, value, meterType FROM measurements
                WHERE meterType = ? AND deviceID IN (
                    SELECT deviceID FROM devices
                        WHERE householdID = (
                            SELECT householdID FROM households
                                WHERE userID = ?))',
                [$type . ' water', $id]);
            return $this->ConvertToObjects($result);
        }
    }

    public function GetRegion(Request $request)
    {
        $region = json_decode(Region::find($request->User()->regionID));  // Eloquent metode bliver brugt her. Find metoden finder et enkelt objekt ud fra PrimaryKey. PrimaryKey findes ud fra den bruger der søger.
        return $region;
    }
}

class DataStore
{
    public $date;
    public $value;
    public $type;

    public function __construct($date, $value, $type)
    {
        $this->type = $type;
        $this->date = $date;
        $this->value = $value;
    }
}
