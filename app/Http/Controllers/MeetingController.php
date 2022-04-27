<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * MeetingController
 */
class MeetingController extends Controller
{
    public function make(Request $request){
        $request->validate([
            'dates' => 'required|array',
            'countryCode' => 'required|string',
            // 'timeZone' => 'required|timezone',
            // 'startTime' => 'required|time',
            // 'stopTime' => 'required|time',
            'day2_Ops' => 'date'
        ]);
        $dates = $request->dates;
        $countryCode = $request->countryCode;
        $timeZone = $request->timeZone;
        $startTime = $request->startTime;
        $stopTime = $request->stopTime;
        $day2_Ops = $request->day2_Ops;

        $availDates = [];
        foreach($dates as $date){
            if(!strtotime($date)){
                return response()->json([
                    'message' => 'dates must be in date format YYYY-MM-DD'
                ], 422);
            }else{
                if ($date !== $day2_Ops) {
                    array_push($availDates, $date);
                }
            }
        }
        // return [$dates, $countryCode]; 
    }
}
