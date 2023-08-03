<?php

namespace App\Helper;


class Helper {
    function APINASA($dateone,$datetwo){
        $ch = curl_init();
        $url = "https://api.nasa.gov/neo/rest/v1/feed?start_date=".$dateone."&end_date=".$datetwo."&detailed=true&api_key=DEMO_KEY";
        //dd($messageObj);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = curl_exec($ch);
        curl_close($ch);
        if($httpCode == 200){
            $response = json_decode($result);
            $finaldata='';
            if(!empty($response->code) && $response->code == 400){
                return $finaldata;
            }
            $objects = $array = (array) $response->near_earth_objects;
            $data = array();
            foreach ($objects as $key => $values){
                $row=array("date"=>$key,"count"=>count($values));
                $data[] = $row;
            }
        }
        return $data;
    }
    function TableNASA($dateone,$datetwo){
        $ch = curl_init();
        $url = "https://api.nasa.gov/neo/rest/v1/feed?start_date=".$dateone."&end_date=".$datetwo."&detailed=true&api_key=DEMO_KEY";
        //dd($messageObj);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result);
        $finaldata='';
        if(!empty($response->code) && $response->code == 400){
            return $finaldata;
        }
        $objects = $array = (array) $response->near_earth_objects;
        $nasadata = array();
        foreach ($objects as $key => $values){
            foreach ($values as $keynew =>$data){
                $average = ($data->estimated_diameter->kilometers->estimated_diameter_max+$data->estimated_diameter->kilometers->estimated_diameter_min)/2;
                $final = array("id"=>$data->id,"velocity"=>$data->close_approach_data[0]->relative_velocity->kilometers_per_hour,"close"=>$data->close_approach_data[0]->miss_distance->kilometers,"average"=>$average);
                $nasadata[] = $final;
            }
        }
        return $nasadata;
    }
}
