<?php

namespace App\Models;

class ApiModel extends \MainModel
{
    public static function GetTime(array $params) {
        $result = [];

        foreach ($params as $value) {
            switch ($value) {
                case 'dateTimeMySQL':
                    $result[] = date('Y-m-d H:i:s');
                    break;
                
                case 'timeUNIX':
                    $result[] = microtime(true);
                    break;
            }
        }

        return $result;
    }
}
