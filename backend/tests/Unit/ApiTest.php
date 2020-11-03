<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Http\Controllers\DataController;
use Symfony\Component\VarDumper\Cloner\Data;


class ApiTest extends TestCase
{

    public function testGetConsumptionHot()
    {
        // Get monthly hot water consumption as a list, using http requeust.
        $response = $this->get('api/data/consumption/hot/list', ['Authorization' => 'Bearer ' . $this->getAccessToken()]);

        // Assert the response status to 200
        $response->assertStatus(200);
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
