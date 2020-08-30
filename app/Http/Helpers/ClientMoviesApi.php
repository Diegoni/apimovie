<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;

class ClientMoviesApi
{
    public static $urlApi = 'https://api.themoviedb.org/3/';
    public static $apiKey = '6f26fd536dd6192ec8a57e94141f8b20';

    public static function getDataFromApi($urlSegment)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => self::getUrlCurl($urlSegment),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => Request::METHOD_GET,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);

        if ($err) {
            throw new RuntimeException("cURL Error #:" . $err );
        } else {
            return $response;
        }
    }

    private static function getUrlCurl($urlSegment)
    {      
        return self::$urlApi.$urlSegment."?api_key=".self::$apiKey;
    }
}
