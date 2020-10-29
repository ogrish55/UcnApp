<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Http\Controllers\DataController;
use Symfony\Component\VarDumper\Cloner\Data;


class MeasurementTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetLatestYearHot()
    {

        $this->assertTrue($this->$actualUsageThisYear = 12);
    }

}
