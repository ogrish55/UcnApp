<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Household;
use App\Models\Device;
use App\Models\Measurement;
use App\Models\User;
use DateTime;
use GrahamCampbell\ResultType\Result;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class DataController extends Controller
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

    public function GetAllData(Request $request, $id)
    {
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
    public function GetDataUser(Request $request, $id, $type)
    {
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


    /**
     * Get the monthly measurements for a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyMeasurements(Request $request, $id, $type)
    {
        $values = $this->GetDataUser($request, $id, $type);

        // find frem til sidste dato i måneden og vælg den seneste værdi og smid over i nyt array
        $onePerMonth = [];

        foreach ($values as $i => $v) {
            $onePerMonth[$i] = $v->date->format('Y-m-t'); // finder sidste dag i måneden for en given dato
        }

        // fjern alle duplicates så der kun eksisterer de datoer der er nødvendige
        $onePerMonthStriped = array_unique($onePerMonth);;


        $monthlyMeasurements = [];

        foreach ($onePerMonthStriped as $i => $o) { // for hver dato
            foreach ($values as $v) { // for hver datasæt
                // hvis datoen passer overens tilføjes dataen til arrayet
                // den overrider samme plads indtil der ikke er flere ved samme dato (aka den får den sidste måling for den data)
                if ($o == $v->date->format('Y-m-d')) {
                    $monthlyMeasurements[$i] = $v;
                }
            }
        }
        return $monthlyMeasurements;
    }

    /**
     * Get the actual consumption per month for a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyConsumption(Request $request, $id, $type)
    {
        $monthlyMeasurements = $this->GetMonthlyMeasurements($request, $id, $type);

        // lav nyt array som tager differencen aka det rigtige forbrug hver måned
        $actualConsumption = [];
        $startValue = $monthlyMeasurements[0]->value; // gemmer den værdi måleren stod på efter første måling ..

        foreach ($monthlyMeasurements as $i => $data) {
            $newObject = new DataStore;
            $newObject->date = $data->date;
            $newObject->value = $data->value - $startValue; // tager målingen og minusser med sidste måneds måling for at få det faktiske forbrug
            $newObject->type = $data->type;
            $startValue = $data->value; // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration

            $actualConsumption[$i] = $newObject;
        }

        return response()->json([
            $actualConsumption]);
    }

    /**
     * Get the average consumption of a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyAverage(Request $request, $id)
    {
        $actualConsumption = $this->GetMonthlyConsumption($request, $id);

        // find gennemsnit af forbrug
        $average = 0.0;

        foreach ($actualConsumption as $data) {
            $average += $data->value; // lægger alle tal sammen
        }

        $average = $average / (count($actualConsumption) - 1); // dividerer så vi får gennemsnit. -1 pga første datasæt altid er 0

        return $average;
    }


    // IKKE FÆRDIG

    // /**
    //  * Get the actual usage for the latests date
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function GetUsageToday(Request $request, $id)
    // {
    //     // få fat på den nyeste måling for i dag
    //     // få fat på seneste måling fra dagen før
    //     // træk tallene fra hinanden for at få dagens faktiske forbrug

    //     // $result = $this->GetDataUser($request, $id);

    //     // evt. gør det ved alle dage i en måned så man kan se hvilke dage man har brugt hvor meget

    //     // SQL query til at få fat på alle målinger for en bruger
    //     $result = DB::select('SELECT measurement, value FROM measurements
    //     WHERE deviceID = (
    //         SELECT deviceID FROM devices
    //         WHERE householdID = (
    //             SELECT householdID FROM households
    //             WHERE userID = ?
    //         )
    //         LIMIT 1, 1
    //     )', [$id]);

    //     // konverter resultatet til objekter og smid i et nyt array
    //     $values = [];

    //     foreach($result as $i => $m){
    //         //værdien
    //         // få fat i value string
    //         $valueString = $m->value;

    //         // fjern sidste 3 karakterer
    //         $string = substr($valueString, 0, strlen($valueString) - 3);

    //         // konverter til int
    //         $value = (double)$string;

    //         //datoen
    //         $dateString = $m->measurement;
    //         $dateString = substr($dateString, 0, 10);

    //         // opret objekt
    //         $data = new DataStore;
    //         $data->date = new DateTime($dateString);
    //         // $date = date_format($date 'Y-m-d H:i:s');
    //         $data->value = $value;

    //         // tilføj objekt til array
    //         $values[$i] = $data;
    //     }


    //     return "penis balls";
    //     // return $result;
    // }


    // ideer til andre
    // samlet månedlig forbrug
    // denne måneds varm forbrug
    // denne måneds kold forbrug

    /**
     * Get the actual usage for the latests month in hot water
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestMonthHot(Request $request, $id)
    {
        // alle varme målinger for en person
        $result = DB::select('SELECT measurement, value FROM measurements
        WHERE deviceID = (
            SELECT deviceID FROM devices
            WHERE householdID = (
                SELECT householdID FROM households
                WHERE userID = ?
            )
            LIMIT 1, 1
        )', [$id]);


        // konverter til objekter
        $values = [];

        foreach ($result as $i => $m) {
            //værdien
            // få fat i value string
            $valueString = $m->value;

            // fjern sidste 3 karakterer
            $string = substr($valueString, 0, strlen($valueString) - 3);

            // konverter til int
            $value = (double)$string;

            //datoen
            $dateString = $m->measurement;
            $dateString = substr($dateString, 0, 10);

            // opret objekt
            $data = new DataStore;
            $data->date = new DateTime($dateString);
            // $date = date_format($date 'Y-m-d H:i:s');
            $data->value = $value;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        $lengthOfArray = count($values) - 1;

        $newArray = [];
        $newArray[0] = array_pop($values); // nyeste entry i database
        // værdi for seneste måling
        $latestMeasurementValue = $newArray[0]->value;

        // find seneste værdi for måneden før
        // først find måneden før gg hvordan?!
        $thisMonth = date_format($newArray[0]->date, 'Y-m'); // output bliver "2020-01" fx
        // $newArray[1] = $thisMonth;

        // while loop - start baglæns og led indtil en data ikke passer med thisMonth - værdien fra den måling er sidste måneds forbrug
        // $penispenis = [];
        $i = $lengthOfArray - 1;
        $found = false;
        $lastMonthValue = 0.0;

        while (!$found) {
            if (date_format($values[$i]->date, 'Y-m') != $thisMonth) {
                $lastMonthValue = $values[$i]->value;
                $found = true;
            }
            $i--;
        }

        // udregn forbrug
        $actualUsageThisMonth = $latestMeasurementValue - $lastMonthValue;


        return $actualUsageThisMonth;
    }

    /**
     * Get the actual usage for the latests month in cold water
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestMonthCold(Request $request, $id)
    {
        // alle varme målinger for en person
        $result = DB::select('SELECT measurement, value FROM measurements
        WHERE deviceID = (
            SELECT deviceID FROM devices
            WHERE householdID = (
                SELECT householdID FROM households
                WHERE userID = ?
            )
            LIMIT 1
        )', [$id]);


        // konverter til objekter
        $values = [];

        foreach ($result as $i => $m) {
            //værdien
            // få fat i value string
            $valueString = $m->value;

            // fjern sidste 3 karakterer
            $string = substr($valueString, 0, strlen($valueString) - 3);

            // konverter til int
            $value = (double)$string;

            //datoen
            $dateString = $m->measurement;
            $dateString = substr($dateString, 0, 10);

            // opret objekt
            $data = new DataStore;
            $data->date = new DateTime($dateString);
            // $date = date_format($date 'Y-m-d H:i:s');
            $data->value = $value;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        $lengthOfArray = count($values) - 1;

        $newArray = [];
        $newArray[0] = array_pop($values); // nyeste entry i database
        // værdi for seneste måling
        $latestMeasurementValue = $newArray[0]->value;

        // find seneste værdi for måneden før
        // først find måneden før gg hvordan?!
        $thisMonth = date_format($newArray[0]->date, 'Y-m'); // output bliver "2020-01" fx
        // $newArray[1] = $thisMonth;

        // while loop - start baglæns og led indtil en data ikke passer med thisMonth - værdien fra den måling er sidste måneds forbrug
        // $penispenis = [];
        $i = $lengthOfArray - 1;
        $found = false;
        $lastMonthValue = 0.0;

        while (!$found) {
            if (date_format($values[$i]->date, 'Y-m') != $thisMonth) {
                $lastMonthValue = $values[$i]->value;
                $found = true;
            }
            $i--;
        }

        // udregn forbrug
        $actualUsageThisMonth = $latestMeasurementValue - $lastMonthValue;


        return $actualUsageThisMonth;
    }

    /**
     * Get the actual usage for the latests month in total (hot + cold water)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestMonthTotal(Request $request, $id)
    {
        $hotWater = $this->GetLatestMonthHot($request, $id);
        $coldWater = $this->GetLatestMonthCold($request, $id);

        return $hotWater + $coldWater;
    }

    /**
     * Get the actual usage for the latests year in hot water
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestYearHot(Request $request, $id)
    {
        // alle varme målinger for en person
        $result = DB::select('SELECT measurement, value FROM measurements
        WHERE deviceID = (
            SELECT deviceID FROM devices
            WHERE householdID = (
                SELECT householdID FROM households
                WHERE userID = ?
            )
            LIMIT 1, 1
        )', [$id]);


        // konverter til objekter
        $values = [];

        foreach ($result as $i => $m) {
            //værdien
            // få fat i value string
            $valueString = $m->value;

            // fjern sidste 3 karakterer
            $string = substr($valueString, 0, strlen($valueString) - 3);

            // konverter til int
            $value = (double)$string;

            //datoen
            $dateString = $m->measurement;
            $dateString = substr($dateString, 0, 10);

            // opret objekt
            $data = new DataStore;
            $data->date = new DateTime($dateString);
            // $date = date_format($date 'Y-m-d H:i:s');
            $data->value = $value;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        $newArray = [];
        $newArray[0] = array_pop($values); // nyeste entry i database
        // // værdi for seneste måling
        $latestMeasurementValue = $newArray[0]->value;

        // find nuværende år
        $thisYear = date_format(array_pop($values)->date, 'Y'); // output bliver "2020" fx


        // // while loop - start baglæns og led indtil en data ikke passer med thisMonth - værdien fra den måling er sidste måneds forbrug
        $i = count($values) - 2;
        $found = false;
        $lastYearValue = 0.0;

        while (!$found) {
            if (date_format($values[$i]->date, 'Y') != $thisYear) {
                $lastYearValue = $values[$i]->value;
                $found = true;
            }
            $i--;
        }

        // // udregn forbrug
        $actualUsageThisYear = $latestMeasurementValue - $lastYearValue;


        return $actualUsageThisYear;
    }

    /**
     * Get the actual usage for the latests year in cold water
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestYearCold(Request $request, $id)
    {
        // alle varme målinger for en person
        $result = DB::select('SELECT measurement, value FROM measurements
        WHERE deviceID = (
            SELECT deviceID FROM devices
            WHERE householdID = (
                SELECT householdID FROM households
                WHERE userID = ?
            )
            LIMIT 1
        )', [$id]);


        // konverter til objekter
        $values = [];

        foreach ($result as $i => $m) {
            //værdien
            // få fat i value string
            $valueString = $m->value;

            // fjern sidste 3 karakterer
            $string = substr($valueString, 0, strlen($valueString) - 3);

            // konverter til int
            $value = (double)$string;

            //datoen
            $dateString = $m->measurement;
            $dateString = substr($dateString, 0, 10);

            // opret objekt
            $data = new DataStore;
            $data->date = new DateTime($dateString);
            // $date = date_format($date 'Y-m-d H:i:s');
            $data->value = $value;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        $newArray = [];
        $newArray[0] = array_pop($values); // nyeste entry i database
        // // værdi for seneste måling
        $latestMeasurementValue = $newArray[0]->value;

        // find nuværende år
        $thisYear = date_format(array_pop($values)->date, 'Y'); // output bliver "2020" fx


        // // while loop - start baglæns og led indtil en data ikke passer med thisMonth - værdien fra den måling er sidste måneds forbrug
        $i = count($values) - 2;
        $found = false;
        $lastYearValue = 0.0;

        while (!$found) {
            if (date_format($values[$i]->date, 'Y') != $thisYear) {
                $lastYearValue = $values[$i]->value;
                $found = true;
            }
            $i--;
        }

        // // udregn forbrug
        $actualUsageThisYear = $latestMeasurementValue - $lastYearValue;


        return $actualUsageThisYear;
    }

    /**
     * Get the actual usage for the latest year in total (hot + cold water)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetLatestYearTotal(Request $request, $id)
    {
        $hotWater = $this->GetLatestYearHot($request, $id);
        $coldWater = $this->GetLatestYearCold($request, $id);

        return $hotWater + $coldWater;
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthNumber(Request $request, $id)
    {
        $result = $this->GetAllData($request, $id);

        $latestMeasurement = array_pop($result);

        $date = date_format($latestMeasurement->date, 'm');

        // konverter til tal
        $monthNumber = (int)$date;

        return $monthNumber;
    }


}

class DataStore
{
    public $date;
    public $value;
    public $type;
}
