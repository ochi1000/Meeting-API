<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimezoneTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_countries_timezone_api()
    {
        $response = $this->get('/api/countries_timezone');

        $response->assertStatus(200);
    }
}
