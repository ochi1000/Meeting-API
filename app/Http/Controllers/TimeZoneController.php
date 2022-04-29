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
        
        /**
         *  check status of API and deliver proper response, 
         * 400 in this case is used for a general case of failed/bad/unauthenticated resquests
        */ 
        $status = ($data->status === 'OK') ? 200 : 400;

        // modified the response messages to fit our intent
        if (empty($data->message)) {
            $data->message = 'success';
        }

        // filter the zone data from response
        $zones = $data->zones;
        foreach ($zones as $key => $value) {
            $country = $value->countryName;
            $timeZone = $value->zoneName;
            // insert each pair into a key value pair array
            array_push($countryZones, ['country'=> $country, 'timeZone'=>$timeZone]);
        }

        // return filtered data
        $timezoneData = [
            'meta'=> [
                'status' => $data->status,
                'message' => $data->message,
            ],
            'data' => $countryZones
        ];
        return response()->json($timezoneData, $status);
    }
}
