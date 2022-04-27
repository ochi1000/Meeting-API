<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

/**
 * The TimeZoneController uses the timezonedb API which is continously updated
 * docs -- https://timezonedb.com/references/get-time-zone --
 */
class TimeZoneController extends Controller
{
    public function index(){
        $countryZones = [];
        // get api response with API key and preferred format
        $response = Http::get('http://api.timezonedb.com/v2.1/list-time-zone', 
        [
            'key'=> '9FY9XUFA1YO2',
            'format' => 'json'
        ]);
        $data = json_decode($response);

        // modified the response messages to fit our intent
        if (empty($data->message)) {
            $data->message = 'success';
        }

        // filter the zone data from response
        $zones = $data->zones;
        foreach ($zones as $key => $value) {
            $countryName = $value->countryName;
            /**
             * convert timezone data to number/integer for divisibility
             * present timezone in std 2 decimal points
             * add + or - depending on current value and append to UTC
             */
            $timeZone = intval($value->gmtOffset)/3600;
            $timeZone = number_format($timeZone, 2);
            $timeZone = $timeZone >= 0 ? '+'.$timeZone : $timeZone;
            $timeZone = 'UTC'.$timeZone;
            array_push($countryZones, ['countryName'=> $countryName, 'timeZone'=>$timeZone]);
        }

        // return filtered data
        $timezoneData = [
            'status' => $data->status,
            'message' => $data->message,
            'countryZones' => $countryZones
        ];
        return $timezoneData;
    }
}
