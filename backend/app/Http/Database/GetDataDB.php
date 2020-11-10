<?php


namespace App\Http\Database;


use App\Models\Device;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetDataDB
{
    public function ConvertToObjects($result)
    {
        // Oprettelse af tomt array, der skal holde de konverterede objekter.
        $objectCollection = [];

        // Iterer igennem hver objekt i $result kollektionen.
        foreach ($result as $m) {
            $data = new DataStore;                          // Opret tomt DataStore object.
            $data->date = new DateTime($m->measurement);    // Konverter tidspunkt for måling til et DateTime objekt.
            $data->value = (double)$m->value;               // Konverter måle værdien til en double.
            $data->type = $m->meterType;                    // Angiv type af måling. (Kold eller varm)

            $objectCollection[] = $data;                    // tilføj objektet til arrayet
        }
        return $objectCollection;
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

        }

        // if $type is 'hot' or 'cold'
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
