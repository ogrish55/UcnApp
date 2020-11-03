<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiTest extends TestCase
{

    public function testGetHotConsumption(){
        // Get monthly hot water consumption as a list, using http request.
        $hotConsumptionList = $this->get('api/data/consumption/hot/list', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $hotConsumptionList->assertStatus(200);
    }

    public function testGetColdConsumption(){
        // Get monthly cold water consumption as a list, using http request.
        $coldConsumptionList = $this->get('api/data/consumption/cold/list', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $coldConsumptionList->assertStatus(200);
    }

    public function testGetMonthlyHotMeasurement(){
        // Get monthly hot water measurements, using http request.
        $hotMonthlyMeasurement = $this->get('api/data/monthlyMeasurements/hot', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $hotMonthlyMeasurement->assertStatus(200);
    }

    public function testGetMonthlyColdMeasurement(){
        // Get monthly cold water measurements, using http request.
        $coldMonthlyMeasurement = $this->get('api/data/monthlyMeasurements/cold', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $coldMonthlyMeasurement->assertStatus(200);
    }

    public function testGetCurrentMonthNumber(){
        // Get current month, using http request.
        $monthNumber = $this->get('api/data/currentYearUsage/total/monthNumber', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $monthNumber->assertStatus(200);
    }

    public function testGetCurrentYearUsageTotal(){
        // Get current year total usage, using http request.
        $currentYearUsage = $this->get('api/data/currentYearUsage/total', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentYearUsage->assertStatus(200);
    }

    public function testGetCurrentYearHotUsage(){
        // Get current year hot water usage, using http request.
        $currentYearHotUsage = $this->get('api/data/currentYearUsage/hot', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentYearHotUsage->assertStatus(200);
    }

    public function testGetCurrentYearColdUsage(){
        // Get current year cold water usage, using http request.
        $currentYearColdUsage = $this->get('api/data/currentYearUsage/cold', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentYearColdUsage->assertStatus(200);
    }

    public function testGetCurrentMonthUsageTotal(){
        // Get current month total usage, using http request.
        $currentMonthUsage = $this->get('api/data/currentMonthUsage/total', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentMonthUsage->assertStatus(200);
    }

    public function testGetCurrentMonthHotUsage(){
        // Get current month hot water usage, using http request.
        $currentMonthHotUsage = $this->get('api/data/currentMonthUsage/hot', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentMonthHotUsage->assertStatus(200);
    }

    public function testGetCurrentMonthColdUsage(){
        // Get current month cold water usage, using http request.
        $currentMonthColdUsage = $this->get('api/data/currentMonthUsage/cold', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $currentMonthColdUsage->assertStatus(200);
    }

    public function testGetAverageHotUsage(){
        // Get average hot water usage, using http request.
        $averageHotUsage = $this->get('api/data/average/hot', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $averageHotUsage->assertStatus(200);
    }

    public function testGetAverageColdUsage(){
        // Get average hot water usage, using http request.
        $averageColdUsage = $this->get('api/data/average/cold', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $averageColdUsage->assertStatus(200);
    }

    public function testGetMonthlyUsageInDkk(){
        // Get monthly usage in DKK, using http request.
        $monthlyUsageDKK = $this->get('api/data/monthlyUsageInDkk', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200 / OK
        $monthlyUsageDKK->assertStatus(200);
    }

    public function testGetUser() {
        // Get user using http request
        $response = json_decode($this->get('/api/user', ['Authorization' => 'Bearer ' . $this->getAccessToken()])->content());

        // Assert the response data
        $this->assertEquals('Jonas', $response->firstName);
        $this->assertEquals('Haxholm', $response->lastName);
        $this->assertEquals('JonasMail@gmail.com', $response->email);
        $this->assertEquals('1', $response->userID);
    }

    private function getAccessToken() {
        $response = json_decode($this->post('/api/login', ['username'=>'JonasMail@gmail.com', 'password'=>'password'])->content());
        return $response->access_token;
    }
}
