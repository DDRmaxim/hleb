<?php

namespace App\Models;

class DefaultModel extends \MainModel
{
    static function load() {
        $url = getFullUrl(getUrlByName('api'));

        $data = [
            "jsonrpc" => "2.0", 
            "method"  => "getTime", 
            "params"  => ["dateTimeMySQL", "timeUNIX"], 
            "id"      => rand()
        ];
        
        $data_json = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $_SERVER['SERVER_ADDR'] . ':' .  $_SERVER['SERVER_PORT']); // for Docker
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

        $headers   = array();
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $result = 'Error: ' . curl_error($ch);
        } else {
            $result = json_decode($result, true);
        }

        curl_close($ch);
        
        return $result;
    }
}
