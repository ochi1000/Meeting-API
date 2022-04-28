<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Label84\HoursHelper\Facades\HoursHelper;

/**
 * MeetingController makes optimal time from data
 *  Uses calendarific api to match holidays -- api docs -- https://calendarific.com/api-documentation
 */
class MeetingController extends Controller
{
    // make optimal time function
    public function make(Request $request){
        // validate all data
        // optional Day2-Operation
        $request->validate([
            'dates' => 'required|array',
            'countryCode' => 'required|string|max:3',
            'timeZone' => 'required|timezone',
            'startTime' => 'required|date_format:H:i',
            'stopTime' => 'required|date_format:H:i|after:startTime',
            'day2_Ops' => 'date'
        ]);
        $dates = $request->dates;
        $countryCode = $request->countryCode;
        $timeZone = $request->timeZone;
        $startTime = $request->startTime;
        $stopTime = $request->stopTime;
        $day2_Ops = $request->day2_Ops;

        $availDates = [];
        $optimalTimes = [];
        // Iterate through dates and filter
        foreach($dates as $date){
            // if input date is invalid return error
            if(!strtotime($date)){
                return response()->json([
                    'message' => 'dates must be in date format YYYY-MM-DD'
                ], 422);
            }else{
                // People invloved in day2 operations would most likely not be available for a meeting, so skip
                if ($date === $day2_Ops) {
                    continue;
                }
                // if date matches with a holiday date, skip
                if($this->matchWithHolidays($countryCode, $date)){
                    continue;
                }
                // insert into available dates array
                array_push($availDates, $date);
            }
        }

        /**
         * if there are any available dates calculate optimal time
         * in this case, optimal times for meeting start an hour after work resumption 
         * and 2 hours before closing time 
        */ 
        if(!empty($availDates)){
            // add an hour to resumption time and subtract 2 hours from close time for optimal meetings and format to desired time format
            $start = Carbon::createFromFormat('H:i', $startTime)->addHour()->format('H:i');
            $end = Carbon::createFromFormat('H:i', $stopTime)->subHours(2)->format('H:i');
    
            $optimalTimes = HoursHelper::create($start, $end, 60);
        }
        $meetingTimes = [
            'countryCode' => $countryCode,
            'timeZone' => $timeZone,
            'available_dates' => $availDates,
            'optimal_times' => $optimalTimes
        ];
        return response()->json($meetingTimes);
    }

    // match holidays function from calendarific api
    public function matchWithHolidays($countryCode, $date){
        // derive year,month and day from parsed date
        $year = date('Y',strtotime($date));
        $month = date('m',strtotime($date));
        $day = date('d',strtotime($date));
        // check response data for available holidays
        $response = Http::get('https://calendarific.com/api/v2/holidays', 
        [
            'api_key'=> 'e891e35b9865c3abf830f9b72d0b1c4dd65b856703ffdb643ad775dbefa92540',
            'country' => $countryCode,
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);
        $data = json_decode($response);
        // return match=true if there are any holidays returned else return false
        if (!empty($data->response->holidays)) {
            $match = true;
        }else{
            $match = false;
        }
        return $match;
    }
}
