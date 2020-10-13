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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetData(Request $request)
    {
        $data = Device::all();
        return response()->json($data);
    }

      /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetDataUser(Request $request, $id)
    {
        // SQL query til at få fat på alle målinger for en bruger
        $result = DB::select('SELECT measurement, value FROM measurements
        WHERE deviceID = (
            SELECT deviceID FROM devices
            WHERE householdID = (
                SELECT householdID FROM households
                WHERE userID = ?

            )
            LIMIT 1, 1
        )', [$id]);

        // konverter resultatet til objekter og smid i et nyt array
        $values = [];

        foreach($result as $i => $m){
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
            $data->value = $value;

            // tilføj objekt til array
            $values[$i] = $data;
        }

        return $values; // returner array af objekter
    }

     /**
     * Get the monthly measurements for a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyMeasurements(Request $request, $id)
    {
        $values = $this->GetDataUser($request, $id);

        // find frem til sidste dato i måneden og vælg den seneste værdi og smid over i nyt array
        $onePerMonth = [];

        foreach($values as $i => $v){
            $onePerMonth[$i] = $v->date->format('Y-m-t'); // finder sidste dag i måneden for en given dato
        }

        // fjern alle duplicates så der kun eksisterer de datoer der er nødvendige
        $onePerMonthStriped = array_unique($onePerMonth);;


        $monthlyMeasurements = [];

        foreach($onePerMonthStriped as $i => $o){ // for hver dato
            foreach($values as $v){ // for hver datasæt
                // hvis datoen passer overens tilføjes dataen til arrayet
                // den overrider samme plads indtil der ikke er flere ved samme dato (aka den får den sidste måling for den data)
                if($o == $v->date->format('Y-m-d')){
                    $monthlyMeasurements[$i] = $v;
                }
             }
        }
        return $monthlyMeasurements;
    }

    /**
     * Get the actual consumption per month for a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyConsumption(Request $request, $id)
    {
        $monthlyMeasurements = $this->GetMonthlyMeasurements($request, $id);

        // lav nyt array som tager differencen aka det rigtige forbrug hver måned
        $actualConsumption = [];
        $startValue = $monthlyMeasurements[0]->value; // gemmer den værdi måleren stod på efter første måling ..

        foreach($monthlyMeasurements as $i => $data){
            $newObject = new DataStore;
            $newObject->date = $data->date;
            $newObject->value = $data->value - $startValue; // tager målingen og minusser med sidste måneds måling for at få det faktiske forbrug

            $startValue = $data->value; // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration

            $actualConsumption[$i] = $newObject;
        }

        return response()->json([
            $actualConsumption
        ], );
    }

     /**
     * Get the average consumption of a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyAverage(Request $request, $id)
    {
        $actualConsumption = $this->GetMonthlyConsumption($request, $id);

        // find gennemsnit af forbrug
        $average = 0.0;

        foreach($actualConsumption as $data){
            $average += $data->value; // lægger alle tal sammen
        }

        $average = $average / (count($actualConsumption) - 1); // dividerer så vi får gennemsnit. -1 pga første datasæt altid er 0

        return $average;
    }

}

class DataStore {
    public $date;
    public $value;
}
