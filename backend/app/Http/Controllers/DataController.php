<?php

namespace App\Http\Controllers;

use App\Http\Database\DataStore;
use App\Http\Database\GetDataDB;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

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
     * @param Request $request
     * @param $type
     * @return array
     */
    public function GetMonthlyMeasurements(Request $request, $type)
    {
        $values = $this->GetDataDB->GetMeasurementsBasedOnType($request, $type);

        $onePerMonth = [];
        foreach ($values as $v) {
            $onePerMonth[] = $v->date->format('Y-m-t');     // Iterer igennem hver date objekt i kollektionen, og ændrer den til den sidste på måneden: f.eks. 2020-09-18 -> 2020-09-30
        }
        $onePerMonthUnique = array_unique($onePerMonth);   // fjern alle duplikater så det kun er den seneste dato for hver måned der eksister: f.eks, 2019-01-31, 2019-02-28 ...
        $monthlyMeasurements = [];
        foreach ($onePerMonthUnique as $i => $o) {
            foreach ($values as $v) {
                if ($o == $v->date->format('Y-m-d')) {      // hvis datoen passer overens tilføjes dataen til arrayet
                    $monthlyMeasurements[$i] = $v;          // den overrider samme plads indtil der ikke er flere ved samme dato (aka den får den sidste måling for den data)
                }
            }
        }
        return $monthlyMeasurements;
    }

    /**
     * Get the actual consumption per month for a user
     *
     * @param Request $request
     * @param $type
     * @param $returnType
     * @return array|JsonResponse
     */
    public function GetMonthlyConsumption(Request $request, $type, $returnType)
    {
        $monthlyMeasurements = $this->GetMonthlyMeasurements($request, $type);
        $actualConsumption = [];
        $startValue = 0;
        foreach ($monthlyMeasurements as $data) {
            $newObject = new DataStore($data->date, round($data->value - $startValue, 3), $data->type); // Opret DataStore objekt med Dato, måling, og målingsType. // value bliver affrundet til 3 decimaler
            $startValue = $data->value;                                                                 // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration
            $actualConsumption[] = $newObject;
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
     * @param Request $request
     * @param $type
     * @return float|int
     */
    public function GetMonthlyAverage(Request $request, $type)
    {
        $actualConsumption = $this->GetMonthlyConsumption($request, $type, 'list');

        $average = 0.0;
        foreach ($actualConsumption as $data) {
            $average += $data->value; // lægger alle tal sammen
        }

        $average = $average / (count($actualConsumption)); // dividerer så vi får gennemsnit.

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
        $result = $this->GetDataDB->GetMeasurementsBasedOnType($request, $type);

        // Får fat i seneste måling
        $latestMeasurement = array_pop($result);
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
     * @param Request $request
     * @param $type
     * @return mixed
     */
    public function GetLatestYear(Request $request, $type)
    {
        // Får enten alle varmt eller koldt vands målinger for en bruger
        $result = $this->GetDataDB->GetMeasurementsBasedOnType($request, $type);

        // Får fat i seneste måling
        $latestMeasurement = array_pop($result);

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
        $result = $this->GetDataDB->GetMeasurementsBasedOnType($request, null);

        // Får fat i den sidste måling
        $latestMeasurement = array_pop($result);

        //Får fat i datoen med formatet m. Formatet m returner en,
        // numerisk repræsentationen af måneden og konverter det til en int.
        $date = (int)date_format($latestMeasurement->date, 'm');

        return $date;
    }

    public function MonthlyUsageInDkk(Request $request)
    {
        $monthlyMeasurements = $this->GetMonthlyConsumption($request, 'all', 'list');
        $region = $this->GetDataDB->GetRegion($request);
        $usageInDkkList = [];

        foreach ($monthlyMeasurements as $m) {
            $usageInDkk = new UsageInDkk();
            $usageInDkk->price = substr($m->value * $region->pricePrCubic, 0, -2);
            $usageInDkk->date = $m->date;
            $usageInDkkList[] = $usageInDkk;
        }
        return response()->json([
            $usageInDkkList]);
    }

    public function UserRegion(Request $request)
    {
        return $this->GetDataDB->GetRegionPrice($request);
    }

    public function GetDailyMeasurements(Request $request, $year, $month, $type)
    {
        $values = $this->GetDataDB->GetMeasurementsBasedOnType($request, $type);

        $filteredArray = [];

        // find månedens nummer (Januar = 01, December = 12)
        $monthNumber = HelperMethods::GetMonthNumber($month);

        // filtrere efter år og måned
        foreach ($values as $v) {
            if (date_format($v->date, 'Y') == $year && date_format($v->date, 'm') == $monthNumber) {
                array_push($filteredArray, $v);
            }
        }

        // only get the latest measurement pr day
        $onePerDay = [];
        $currentDay = date_format($filteredArray[0]->date, 'd');

        foreach ($filteredArray as $i => $m) {
            if (date_format($m->date, 'd') != $currentDay) {
                array_push($onePerDay, $filteredArray[$i - 1]);
                $currentDay = date_format($m->date, 'd');
            }
        }
        array_push($onePerDay, $filteredArray[count($filteredArray) - 1]); // tilføj sidste dag da den ikke kommmer med i foreach

        // Get actual consumption instead of measurements
        $startValue = $onePerDay[0]->value; // - (rand(300,1000)/10000); // giver et tal mellem 0,03 og 0,1 som første dags forbrug
        $actualConsumption = [];

        foreach ($onePerDay as $v) {
            $newObject = new DataStore($v->date, round($v->value - $startValue, 3), $v->type); // afrunder til 3 decimaler
            $startValue = $v->value; // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration

            $actualConsumption[] = $newObject;
        }

        return $actualConsumption;
    }

    public function UserAconto(Request $request)
    {
        return $this->GetDataDB->GetAconto($request);
    }

    public function GetDailyMeasurementsAll(Request $request)
    {
        $values = $this->GetDataDB->GetMeasurementsBasedOnType($request, 'hot');

        // only get the latest measurement pr day
        $onePerDay = [];
        $currentDay = date_format($values[0]->date, 'd');

        foreach ($values as $i => $m) {
            if (date_format($m->date, 'd') != $currentDay) {
                array_push($onePerDay, $values[$i - 1]);
                $currentDay = date_format($m->date, 'd');
            }
        }


        // konverter til objekter af typen DailyMeasurements
        // find frem til værdierne til de forskellige attributter
        $convertedArray = [];
        $startValue = $onePerDay[0]->value;

        foreach ($onePerDay as $i => $val) {
            $year = date_format($val->date, 'Y');
            $month = HelperMethods::GetMonthName(date_format($val->date, 'm'));
            $day = date_format($val->date, 'd');
            $value = $val->value;
            $usage = round(($val->value - $startValue) * 1000, 2); // for at værdien er i cm3 og formateret til 1 decimaler
            $dayOfWeek = HelperMethods::GetDayNameDanish(date('l', strtotime(date_format($onePerDay[$i]->date, 'Y-m-d')))); // får ugedagen som navn ved at bruge date 'l' og strtotime hvilket oversættes til dansk med hjælpemetode

            $measurement = new DailyMeasurement($year, $month, $day, $dayOfWeek, $value, $usage); // opretter objekt

            array_push($convertedArray, $measurement); // pusher til arrayet

            $startValue = $val->value; // sætter en ny startværdi
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
    public $weekday;
    public $value;
    public $usage;

    public function __construct($year, $month, $day, $weekday, $value, $usage)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->weekday = $weekday;
        $this->value = $value;
        $this->usage = $usage;
    }
}

class HelperMethods
{

    public static function GetMonthNumber($month)
    {
        $monthNumber = 0;

        switch ($month) {
            case "Januar":
                $monthNumber = 1;
                break;
            case "Februar":
                $monthNumber = 2;
                break;
            case "Marts":
                $monthNumber = 3;
                break;
            case "April":
                $monthNumber = 4;
                break;
            case "Maj":
                $monthNumber = 5;
                break;
            case "Juni":
                $monthNumber = 6;
                break;
            case "Juli":
                $monthNumber = 7;
                break;
            case "August":
                $monthNumber = 8;
                break;
            case "September":
                $monthNumber = 9;
                break;
            case "Oktober":
                $monthNumber = 10;
                break;
            case "November":
                $monthNumber = 11;
                break;
            case "December":
                $monthNumber = 12;
                break;
        }

        return $monthNumber;
    }

    public static function GetMonthName($monthNumber)
    {
        $monthName = "Undefined";

        switch ($monthNumber) {
            case 1:
                $monthName = "Januar";
                break;
            case 2:
                $monthName = "Februar";
                break;
            case 3:
                $monthName = "Marts";
                break;
            case 4:
                $monthName = "April";
                break;
            case 5:
                $monthName = "Maj";
                break;
            case 6:
                $monthName = "Juni";
                break;
            case 7:
                $monthName = "Juli";
                break;
            case 8:
                $monthName = "August";
                break;
            case 9:
                $monthName = "September";
                break;
            case 10:
                $monthName = "Oktober";
                break;
            case 11:
                $monthName = "November";
                break;
            case 12:
                $monthName = "December";
                break;
        }

        return $monthName;
    }

    public static function GetDayNameDanish($weekday)
    {
        $translated = "Undefined";

        switch ($weekday) {
            case "Monday":
                $translated = "Mandag";
                break;
            case "Tuesday":
                $translated = "Tirsdag";
                break;
            case "Wednesday":
                $translated = "Onsdag";
                break;
            case "Thursday":
                $translated = "Torsdag";
                break;
            case "Friday":
                $translated = "Fredag";
                break;
            case "Saturday":
                $translated = "Lørdag";
                break;
            case "Sunday":
                $translated = "Søndag";
                break;
        }

        return $translated;
    }
}
