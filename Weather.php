<?php

class Weather
{
    const TOKEN = '140f55cb30bd4da7b8c94867f579e6db';
    const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather?';
    const BILA = 712165;
    const KYIV = 703448;


    static public function getWeather($city){

        $data = ['id'=>$city,'units'=>'metric', 'appid'=>self::TOKEN];
        $query = http_build_query($data);
        $result = json_decode( file_get_contents(self::BASE_URL . $query), JSON_OBJECT_AS_ARRAY);
        $weather = $result['weather'];
        $main = $result['main'];


        return ['weather'=>$weather, 'main'=>$main];
    }
}

