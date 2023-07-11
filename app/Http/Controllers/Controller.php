<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
        $zk = new ZktecoLib('192.168.20.51', '4370');
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            //dd($attendance);
            return view('zkteco::app', compact('attendance'));
        }
    }
}
