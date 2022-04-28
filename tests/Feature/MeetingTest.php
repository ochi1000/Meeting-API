<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_meeting_make()
    {
        $response = $this->postJson('/api/meeting', 
        [
            'dates'=> ['2022-09-07'],
            'countryCode' => 'NG',
            'timeZone' => 'Africa/Lagos',
            'startTime' => '09:00',
            'stopTime' => '17:00'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test match with holidays
     */
    public function test_matching(){
        $this->get('https://calendarific.com/api/v2/holidays', 
        [
            'api_key'=> 'e891e35b9865c3abf830f9b72d0b1c4dd65b856703ffdb643ad775dbefa92540',
            'country' => 'NG',
            'year' => '2022',
            'month' => '05',
            'day' => '04',
        ]);
    }
}
