<?php

namespace App\Controllers;

use App\Models\DefaultModel;

class DefaultController extends \MainController
{
    public function index() {
        return view("default");
    }

    public function load() {
        $request = DefaultModel::load();

        $data = ["NaN", "NaN"];

        if ($request["jsonrpc"] == "2.0") {
            if (isset($request["result"])) {
                $data   = $request["result"];
                $req_id = '#' . $request["id"];
            }
        }

        return view("default", ['data' => $data, 'req_id' => $req_id]);
    }
}
