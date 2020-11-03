<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDatabaseHasUser()
    {
        // Assert that the database has a table named 'users' and a firstName with 'Jonas' as value.
        $this->assertDatabaseHas('users', ['firstName' => 'Jonas']);
    }
}
