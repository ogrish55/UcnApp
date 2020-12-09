<?php


namespace App\Http\Database;


use Illuminate\Http\Request;

interface iDatabase
{
    public function GetAconto(Request $request);
    public function GetMeasurementsBasedOnType(Request $request, $type);
    public function GetRegion(Request $request);
    public function GetRegionPrice(Request $request);
}
