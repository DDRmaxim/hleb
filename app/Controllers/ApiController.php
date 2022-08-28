<?php

namespace App\Controllers;

use Hleb\Constructor\Handlers\Request;
use App\Models\ApiModel;

class ApiController extends \MainController
{
    public function api() {
        $data = Request::getJsonBodyList();

        try {
            if (count($data) == 1) {
                $complete = self::parse($data[0]);
            } else if ($data["jsonrpc"] != "2.0") {
                foreach ($data as $value) {
                    $complete[] = self::parse($value);
                }
            } else {
                $complete = self::parse($data);
            }
        } catch (\Throwable $th) {
            $complete = self::parse($data);
        }

        header('content-type: application/json; charset=UTF-8');
        echo json_encode($complete, JSON_UNESCAPED_UNICODE);
    }

    private function parse($data) {
        $complete = [ "jsonrpc" => "2.0" ];

        if ($data["jsonrpc"] == "2.0") {
            $method = $data["method"];
            $params = $data["params"];
            $_id = $data["id"];

            $model = "App\\Models\\ApiModel";
    
            if (method_exists($model, $method)) {
                $data = ApiModel::$method($params);
                
                $complete["result"] = $data;
            } else {
                $complete["error"] = ["code" => -32601, "message" => "Method not found"];
            }

            $complete["id"] = $_id;
        } else {
            $data = Request::getInputBody();

            if (!strpos($data, "jsonrpc")) {
                $complete["error"] = ["code" => -32600, "message" => "Invalid Request"];
            } else {
                $complete["error"] = ["code" => -32700, "message" => "Parse error"];
            }
            
            $complete["id"] = null;
        }

        return $complete;
    }
}
