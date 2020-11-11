<?php

namespace App\Http\Controllers;

use App\Http\Database\DataStore;
use App\Http\Database\GetDataDB;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public $GetDataDB;

    public function __construct()
    {
        $this->GetDataDB = new GetDataDB();
    }


    /**
     * Get the monthly measurements for a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyMeasurements(Request $request, $type)
    {
        // $values = null;


        if ($type == 'all') {
            $values = $this->GetDataDB->GetAllData($request);
        } else if ($type == 'hot' || $type == 'cold') {
            $values = $this->GetDataDB->GetDataUser($request, $type);
        }

        // find frem til sidste dato i måneden og vælg den seneste værdi og smid over i nyt array
        $onePerMonth = [];

        foreach ($values as $i => $v) {
            array_push($onePerMonth, $v->date->format('Y-m-t')); // finder sidste dag i måneden for en given dato
        }

        // fjern alle duplikater så der kun eksisterer de datoer der er nødvendige
        $onePerMonthStriped = array_unique($onePerMonth);

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
    public function GetMonthlyConsumption(Request $request, $type, $returnType)
    {
        $monthlyMeasurements = $this->GetMonthlyMeasurements($request, $type);
        // lav nyt array som tager differencen
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
        if ($returnType == 'list') {
            return $actualConsumption;
        } else if ($returnType == 'json') {
            return response()->json([
                $actualConsumption]);
        }

    }

    /**
     * Get the average consumption of a user
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function GetMonthlyAverage(Request $request, $type)
    {
        $returnType = 'list';
        $actualConsumption = $this->GetMonthlyConsumption($request, $type, $returnType);
        
        // find gennemsnit af forbrug
        $average = 0.0;

        foreach ($actualConsumption as $data) {
            $average += $data->value; // lægger alle tal sammen
        }

        $average = $average / (count($actualConsumption) - 1); // dividerer så vi får gennemsnit. -1 pga første datasæt altid er 0

        return $average;
    }


    /**
     * @param Request $request
     * @param $type
     * @return mixed
     */
    public function GetLatestMonth(Request $request, $type)
    {
        // Får enten alle varmt eller koldt vands målinger for en bruger
        $result = $this->GetDataDB->GetDataUser($request, $type);

        // Får fat i seneste måling
        $latestMeasurement = $this->GetLastItem($result);

        $i = count($result) - 1;
        $lastMonthsMeasurement = null;
        $found = false;

        // Starter fra bunden af listen og looper indtil der findes den første måling på listen som ikke har samme årstal og måned
        // som den seneste måling
        while (!$found) {
            if (date_format($result[$i]->date, 'Y-m') != date_format($latestMeasurement->date, 'Y-m')) {
                $lastMonthsMeasurement = $result[$i];
                $found = true;
            }
            $i--;
        }

        // udregn forbrug
        $actualUsageThisMonth = $latestMeasurement->value - $lastMonthsMeasurement->value;


        return $actualUsageThisMonth;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function GetLatestMonthTotal(Request $request)
    {
        $hotWater = $this->GetLatestMonth($request, $type = 'hot');
        $coldWater = $this->GetLatestMonth($request, $type = 'cold');

        return $hotWater + $coldWater;
    }

    /**
     * @param $result
     * @return mixed
     */
    public function GetLastItem($result)
    {
        return array_pop($result);
    }

    /**
     * @param Request $request
     * @param $type
     * @return mixed
     */
    public function GetLatestYear(Request $request, $type)
    {
        // Får enten alle varmt eller koldt vands målinger for en bruger
        $result = $this->GetDataDB->GetDataUser($request, $type);

        // Får fat i seneste måling
        $latestMeasurement = $this->GetLastItem($result);

        $i = count($result) - 1;
        $lastYearsMeasurement = null;
        $found = false;

        // Starter fra bunden af listen og looper indtil der findes den første måling på listen som ikke har samme årstal som den seneste måling
        while (!$found) {
            if (date_format($result[$i]->date, 'Y') != date_format($latestMeasurement->date, 'Y')) {

                $lastYearsMeasurement = $result[$i];
                $found = true;
            }
            $i--;
        }

        // // udregner forbrug
        $actualUsageThisYear = $latestMeasurement->value - $lastYearsMeasurement->value;


        return $actualUsageThisYear;
    }

    /**
     * @param Request $request
     * @return float|\Illuminate\Http\Response
     */
    public function GetLatestYearTotal(Request $request)
    {
        $hotWater = $this->GetLatestYear($request, $type = 'hot');
        $coldWater = $this->GetLatestYear($request, $type = 'cold');

        return $hotWater + $coldWater;
    }


    /**
     * @param Request $request
     * @return int
     */
    public function GetMonthNumber(Request $request)
    {
        // Henter målinger for både koldt og varmt vand
        $result = $this->GetDataDB->GetAllData($request);

        // Får fat i den sidste måling
        $latestMeasurement = array_pop($result);

        //Får fat i datoen med formatet m. Formatet m returner en,
        // numerisk repræsentationen af måneden og konverter det til en int.
        $date = (int)date_format($latestMeasurement->date, 'm');

        return $date;
    }

    public function MonthlyUsageInDkk(Request $request)
    {
        $monthlyMeasurements = $this->GetMonthlyConsumption($request, $type = 'all', $returnType= 'list');
        $regionStore = $this->GetDataDB->GetPricePrCubic($request);
        $UsageInDkkList = [];


        foreach ($monthlyMeasurements as $m) {
            $usageInDkk = new UsageInDkk();
            $usageInDkk->price = substr($m->value * $regionStore->pricePrCubic, 0, -2);
            $usageInDkk->date = $m->date;

            array_push($UsageInDkkList, $usageInDkk);
        }
        return response()->json([
            $UsageInDkkList]);
    }

    public function GetDailyMeasurements(Request $request, $year, $month){
        $values = $this->GetDataDB->GetDataUser($request, 'hot');

        $filteredArray = [];

        // find månedens nummer (Januar = 01, December = 12)
        $monthNumber = HelperMethods::GetMonthNumber($month);

        // filtrer efter år
        foreach ($values as $v){
            if(date_format($v->date, 'Y') == $year && date_format($v->date, 'm') == $monthNumber){
                array_push($filteredArray, $v);
            }
        }

        // only get the latest measurement pr day
        $onePerDay = [];
        $currentDay = date_format($filteredArray[0]->date, 'd');

        foreach ($filteredArray as $i => $m){
            if(date_format($m->date, 'd') != $currentDay){
                array_push($onePerDay, $filteredArray[$i - 1]);
                $currentDay = date_format($m->date, 'd');
            }
        }

        // Get actual consumption instead of measurements
        $startValue = $onePerDay[0]->value - (rand(300,1000)/10000); // giver et tal mellem 0,03 og 0,1 som første dags forbrug
        $actualConsumption = [];

        foreach ($onePerDay as $v){
            $newObject = new DataStore; // SKAL LAVES OM NÅR JEG FÅR KRIS' ÆNDRINGER
            $newObject->date = $v->date;
            $newObject->value = $v->value - $startValue;
            $newObject->type = $v->type;
            $startValue = $v->value; // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration

            $actualConsumption[] = $newObject;
        }

        return $actualConsumption;
    }

    public function GetDailyMeasurementsAll(Request $request){
        $values = $this->GetDataDB->GetDataUser($request, 'hot');

        // only get the latest measurement pr day
        $onePerDay = [];
        $currentDay = date_format($values[0]->date, 'd');

        foreach ($values as $i => $m){
            if(date_format($m->date, 'd') != $currentDay){
                array_push($onePerDay, $values[$i - 1]);
                $currentDay = date_format($m->date, 'd');
            }
        }

        // konverter til objekter af typen DailyMeasurements
        // find frem til værdierne til de forskellige attributter
        $convertedArray = [];
        foreach ($onePerDay as $i => $val){
            $measurement = new DailyMeasurement;
            $measurement->year = date_format($val->date, 'Y');
            $measurement->month = HelperMethods::GetMonthName(date_format($val->date, 'm'));
            $measurement->day = date_format($val->date, 'd');
            $measurement->value = $val->value;

            array_push($convertedArray, $measurement);
        }
        
        // flip the array so the newest measurements are lowest elements (for frontend purposes)
        $reversedArray = array_reverse($convertedArray);

        // done
        return $reversedArray;
    }
}

class UsageInDkk
{
    public $price;
    public $date;
}

class DailyMeasurement
{
    public $year;
    public $month;
    public $day;
    public $value;
}

class HelperMethods {
    
    public static function GetMonthNumber($month){
        $monthNumber = 0;

        switch($month){
            case "Januar":
                $monthNumber = 1; break;
            case "Februar":
                $monthNumber = 2; break;
            case "Marts":
                $monthNumber = 3; break;
            case "April":
                $monthNumber = 4; break;  
            case "Maj":
                $monthNumber = 5; break;
            case "Juni":
                $monthNumber = 6; break;
            case "Juli":
                $monthNumber = 7; break;
            case "August":
                $monthNumber = 8; break;
            case "September":
                $monthNumber = 9; break;
            case "Oktober":
                $monthNumber = 10; break;
            case "November":
                $monthNumber = 11; break;
            case "December":
                $monthNumber = 12; break;    
        }

        return $monthNumber;
    }

    public static function GetMonthName($monthNumber){
        $monthName = "Undefined";

        switch($monthNumber){
            case 1:
                $monthName = "Januar"; break;
            case 2:
                $monthName = "Februar"; break;
            case 3:
                $monthName = "Marts"; break;
            case 4:
                $monthName = "April"; break;  
            case 5:
                $monthName = "Maj"; break;
            case 6:
                $monthName = "Juni"; break;
            case 7:
                $monthName = "Juli"; break;
            case 8:
                $monthName = "August"; break;
            case 9:
                $monthName = "September"; break;
            case 10:
                $monthName = "Oktober"; break;
            case 11:
                $monthName = "November"; break;
            case 12:
                $monthName = "December"; break;    
        }

        return $monthName;
    }
}

