<?php

namespace App\Http\Controllers;

use App\Http\Database\DataStore;
use App\Http\Database\GetDataDB;
use Illuminate\Http\JsonResponse;
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
            $newObject = new DataStore($data->date, $data->value - $startValue, $data->type); // Opret DataStore objekt med Dato, måling, og målingsType.
            $startValue = $data->value;                                                             // sætter startValue til at være dette måneds værdi så den kan bruges i næste iteration
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
}

class UsageInDkk
{
    public $price;
    public $date;
}
